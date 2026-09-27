<?php

namespace App\Http\Controllers;

use App\Enums\AuditEvent;
use App\Enums\DocumentPermission;
use App\Models\DocumentShare;
use App\Services\DocumentAuditLogger;
use App\Services\DocumentVersionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Public-facing endpoints for external share links (no panel login
 * required). Every request is validated against the share's expiry,
 * revocation status, download limit, and optional password, and every
 * outcome — success or denial — is written to the audit trail.
 */
class DocumentShareLinkController extends Controller
{
    public function __construct(
        private readonly DocumentVersionService $versions,
        private readonly DocumentAuditLogger $audit,
    ) {}

    /**
     * Landing page shown before the file is released — never redirect
     * straight to a downloadable file from an unauthenticated GET.
     */
    public function show(string $token): View
    {
        $share = DocumentShare::where('token', $token)->firstOrFail();

        if (! $share->isUsable()) {
            $this->audit->log(AuditEvent::ShareLinkAccessDenied, $share->document, [
                'share_id' => $share->id,
                'reason' => 'expired_or_revoked',
            ]);

            abort(410, 'This share link is no longer valid.');
        }

        return view('dms.share-link', [
            'share' => $share,
            'requiresPassword' => $share->password_hash !== null,
        ]);
    }

    public function download(string $token, Request $request)
    {
        $share = DocumentShare::where('token', $token)->firstOrFail();

        if (! $share->isUsable()) {
            $this->audit->log(AuditEvent::ShareLinkAccessDenied, $share->document, [
                'share_id' => $share->id,
                'reason' => 'expired_or_revoked',
            ]);

            abort(410, 'This share link is no longer valid.');
        }

        if (! $share->permission->atLeast(DocumentPermission::Download)) {
            abort(403, 'This link only allows viewing, not downloading.');
        }

        if (! $share->checkPassword($request->input('password'))) {
            $this->audit->log(AuditEvent::ShareLinkAccessDenied, $share->document, [
                'share_id' => $share->id,
                'reason' => 'bad_password',
            ]);

            abort(403, 'Incorrect password.');
        }

        $version = $share->document->currentVersion;
        $plaintext = $this->versions->decryptVersion($version);

        $share->increment('download_count');
        $share->update(['last_accessed_at' => now()]);

        $this->audit->log(AuditEvent::ShareLinkAccessed, $share->document, [
            'share_id' => $share->id,
            'version_number' => $version->version_number,
        ]);

        return response()->streamDownload(
            fn() => print($plaintext),
            $version->original_filename,
            ['Content-Type' => $version->mime_type]
        );
    }
}
