<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'contract_no',
        'contract_type',
        'original_value',
        'current_value',
        'retention_percentage',
        'payment_terms',
        'effective_date',
        'status',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'original_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'retention_percentage' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
