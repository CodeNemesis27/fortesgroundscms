<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentVersion extends Model
{
    protected $fillable = [
        'document_id',
        'version_number',
        'original_filename',
        'mime_type',
        'size_bytes',
        'storage_path',
        'sha256_checksum',
        'encrypted_data_key',
        'encryption_iv',
        'encryption_tag',
        'encryption_cipher',
        'uploaded_by',
        'change_notes',
    ];

    // Encryption material should never be exposed via API responses,
    // array/JSON casting, or accidental array_merge() into a view.
    protected $hidden = [
        'encrypted_data_key',
        'encryption_iv',
        'encryption_tag',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function humanSize(): string
    {
        return number_format($this->size_bytes / 1024, 1) . ' KB';
    }
}
