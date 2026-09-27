<?php

namespace App\Models;

use App\Enums\AuditEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RuntimeException;

class DocumentAuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'user_id',
        'event',
        'description',
        'ip_address',
        'user_agent',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'event' => AuditEvent::class,
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Audit rows are immutable by design. This is defence-in-depth against
     * tampering — even code with model access cannot silently alter or
     * erase history, only ever append to it.
     */
    public function save(array $options = [])
    {
        if ($this->exists) {
            throw new RuntimeException('Audit log entries are immutable and cannot be modified.');
        }

        return parent::save($options);
    }

    public function delete()
    {
        throw new RuntimeException('Audit log entries cannot be deleted.');
    }
}
