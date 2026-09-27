<?php

return [

    /*
    |--------------------------------------------------------------------
    | Storage disk
    |--------------------------------------------------------------------
    |
    | Encrypted document blobs are written to this disk. It MUST be a
    | private disk (never 'public') — define it in config/filesystems.php,
    | e.g.:
    |
    | 'documents' => [
    |     'driver' => 'local',
    |     'root' => storage_path('app/private/documents'),
    |     'visibility' => 'private',
    |     'throw' => true,
    | ],
    |
    | In production, prefer an S3-compatible disk with SSE enabled as a
    | second layer of encryption underneath the application-level
    | encryption performed by DocumentEncryptionService.
    |
    */
    'disk' => env('DMS_STORAGE_DISK', 'documents'),

    /*
    |--------------------------------------------------------------------
    | Cipher
    |--------------------------------------------------------------------
    |
    | AES-256-GCM is an authenticated cipher: it detects tampering or
    | corruption of the ciphertext at decrypt time (see
    | DocumentEncryptionService::decrypt()), which plain AES-CBC does not.
    |
    */
    'cipher' => 'aes-256-gcm',

    /*
    |--------------------------------------------------------------------
    | Version retention
    |--------------------------------------------------------------------
    */
    'max_versions_per_document' => env('DMS_MAX_VERSIONS', 50),

    /*
    |--------------------------------------------------------------------
    | External share links
    |--------------------------------------------------------------------
    */
    'share_link' => [
        'default_expiry_hours' => env('DMS_SHARE_LINK_EXPIRY_HOURS', 72),
        'token_length' => 40,
    ],

    /*
    |--------------------------------------------------------------------
    | Upload constraints
    |--------------------------------------------------------------------
    |
    | Always validate by MIME type AND extension, never trust the
    | client-supplied Content-Type alone. Consider adding a virus-scan
    | hook (e.g. ClamAV) in DocumentVersionService::storeNewVersion()
    | before ciphertext is written to disk.
    |
    */
    'allowed_mime_types' => [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/png',
        'image/jpeg',
        'text/plain',
    ],

    'max_upload_size_kb' => env('DMS_MAX_UPLOAD_KB', 51200), // 50 MB

];
