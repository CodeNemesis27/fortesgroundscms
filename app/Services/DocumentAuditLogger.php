<?php

namespace App\Services;

use App\Enums\AuditEvent;
use App\Models\Document;
use App\Models\DocumentAuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Single entry point for writing audit trail entries. Centralising this
 * means every code path (Filament actions, the public share-link
 * controller, console commands, queued jobs) records the same shape of
 * event with the same context, rather than each call site rolling its own.
 */

class DocumentAuditLogger
{
    public function log(
        AuditEvent $event,
        ?Document $document = null,
        array $metadata = [],
        ?string $description = null,
    ): DocumentAuditLog {
        return DocumentAuditLog::create([
            'document_id' => $document?->id,
            'user_id' => Auth::id(),
            'event' => $event,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => substr((string) Request::userAgent(), 0, 255),
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }
}
