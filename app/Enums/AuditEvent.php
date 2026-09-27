<?php

namespace App\Enums;

enum AuditEvent: string
{
    case Uploaded = 'document.uploaded';
    case Viewed = 'document.viewed';
    case Downloaded = 'document.downloaded';
    case VersionCreated = 'document.version_created';
    case VersionRestored = 'document.version_restored';
    case Updated = 'document.updated';
    case Shared = 'document.shared';
    case ShareRevoked = 'document.share_revoked';
    case ShareLinkAccessed = 'document.share_link_accessed';
    case ShareLinkAccessDenied = 'document.share_link_access_denied';
    case Deleted = 'document.deleted';
    case Restored = 'document.restored';
    case AccessDenied = 'document.access_denied';
}
