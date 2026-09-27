<?php

namespace App\Models;

use App\Enums\DocumentPermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DocumentShare extends Model
{
    protected $fillable = [
        'document_id',
        'shared_by',
        'shared_with_user_id',
        'permission',
        'token',
        'password_hash',
        'max_downloads',
        'download_count',
        'expires_at',
        'revoked_at',
        'last_accessed_at',
    ];

    protected $casts = [
        'permission' => DocumentPermission::class,
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
        'last_accessed_at' => 'datetime',
    ];

    protected $hidden = ['password_hash', 'token'];

    protected static function booted(): void
    {
        static::creating(function (DocumentShare $share) {
            // External link share: generate a high-entropy, unguessable token.
            if ($share->shared_with_user_id === null && ! $share->token) {
                $share->token = Str::random(64);
            }
        });
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function sharedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_by');
    }

    public function sharedWith(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_with_user_id');
    }

    public function isRevoked(): bool
    {
        return $this->revoked_at !== null;
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isDownloadLimitReached(): bool
    {
        return $this->max_downloads !== null && $this->download_count >= $this->max_downloads;
    }

    public function isUsable(): bool
    {
        return ! $this->isRevoked() && ! $this->isExpired() && ! $this->isDownloadLimitReached();
    }

    public function checkPassword(?string $password): bool
    {
        if (! $this->password_hash) {
            return true;
        }

        return $password !== null && Hash::check($password, $this->password_hash);
    }
}
