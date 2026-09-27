<?php

namespace App\Services;

use App\Enums\AuditEvent;
use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use RuntimeException;

/**
 * Implements the core workflow: Upload -> Encrypt -> Store -> Version.
 *
 * Every write to a document's contents — whether the very first upload or
 * a later revision — flows through storeNewVersion(), so encryption,
 * checksums, and version numbering are applied identically and can never
 * be bypassed by a caller that forgets a step.
 */
class DocumentVersionService
{
    public function __construct(
        private readonly DocumentEncryptionService $encryption,
        private readonly DocumentAuditLogger $audit,
    ) {}

    /**
     * Full pipeline for a brand new document.
     */
    public function createDocument(array $attributes, UploadedFile $file, User $owner): Document
    {
        return DB::transaction(function () use ($attributes, $file, $owner) {
            $document = Document::create([
                ...$attributes,
                'owner_id' => $owner->id,
                'status' => 'active',
            ]);

            $version = $this->storeNewVersion($document, $file, $owner, 'Initial upload');

            $document->forceFill(['current_version_id' => $version->id])->save();

            $this->audit->log(AuditEvent::Uploaded, $document, [
                'version_number' => $version->version_number,
                'filename' => $version->original_filename,
            ]);

            return $document->fresh(['currentVersion']);
        });
    }

    /**
     * Upload -> encrypt -> store a new version of an existing document.
     * The previous version's ciphertext is never touched or deleted.
     */
    public function addVersion(Document $document, UploadedFile $file, User $uploader, ?string $notes = null): DocumentVersion
    {
        return DB::transaction(function () use ($document, $file, $uploader, $notes) {
            $versionCount = $document->versions()->count();

            if ($versionCount >= config('dms.max_versions_per_document')) {
                throw new RuntimeException('This document has reached its maximum number of retained versions.');
            }

            $version = $this->storeNewVersion($document, $file, $uploader, $notes);

            $document->forceFill(['current_version_id' => $version->id])->save();

            $this->audit->log(AuditEvent::VersionCreated, $document, [
                'version_number' => $version->version_number,
                'filename' => $version->original_filename,
            ]);

            return $version;
        });
    }

    private function readUploadedContents(UploadedFile $file): string
    {
        // Livewire-backed temp uploads (FileUpload with storeFiles(false)) may
        // live on a remote disk rather than local — e.g. when Livewire's temp
        // upload disk is set to R2/S3, which is common on ephemeral hosts like
        // Laravel Cloud. getRealPath() only returns a usable path for genuinely
        // local files, so file_get_contents() on it fails otherwise. get()
        // reads correctly either way.
        if ($file instanceof TemporaryUploadedFile) {
            return $file->get();
        }

        return file_get_contents($file->getRealPath());
    }

    private function storeNewVersion(Document $document, UploadedFile $file, User $uploader, ?string $notes): DocumentVersion
    {
        $plaintext = $this->readUploadedContents($file);
        $checksum = hash('sha256', $plaintext);

        $encrypted = $this->encryption->encrypt($plaintext);

        $nextVersionNumber = (int) $document->versions()->max('version_number') + 1;

        // Random, non-guessable storage path — never the original filename —
        // so a directory listing or path traversal leak reveals nothing.
        $storagePath = sprintf('%s/v%d-%s.enc', $document->uuid, $nextVersionNumber, Str::random(20));

        Storage::disk(config('dms.disk'))->put($storagePath, $encrypted['ciphertext']);

        unset($plaintext); // release the plaintext buffer reference as soon as possible

        return DocumentVersion::create([
            'document_id' => $document->id,
            'version_number' => $nextVersionNumber,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size_bytes' => $file->getSize(),
            'storage_path' => $storagePath,
            'sha256_checksum' => $checksum,
            'encrypted_data_key' => $encrypted['encrypted_data_key'],
            'encryption_iv' => $encrypted['iv'],
            'encryption_tag' => $encrypted['tag'],
            'encryption_cipher' => $encrypted['cipher'],
            'uploaded_by' => $uploader->id,
            'change_notes' => $notes,
        ]);
    }

    /**
     * Decrypt a version's contents and verify integrity against the stored
     * checksum. Throws if the ciphertext was tampered with, corrupted, or
     * the checksum no longer matches.
     *
     * Note: this buffers the whole file in memory, which is fine for
     * typical office-document sizes. For very large files, swap this for
     * a streaming decrypt (chunked openssl_decrypt calls) before going to
     * production.
     */
    public function decryptVersion(DocumentVersion $version): string
    {
        $ciphertext = Storage::disk(config('dms.disk'))->get($version->storage_path);

        $plaintext = $this->encryption->decrypt(
            $ciphertext,
            $version->encrypted_data_key,
            $version->encryption_iv,
            $version->encryption_tag
        );

        if (! hash_equals($version->sha256_checksum, hash('sha256', $plaintext))) {
            throw new RuntimeException('Checksum mismatch after decryption — document integrity could not be verified.');
        }

        return $plaintext;
    }
}
