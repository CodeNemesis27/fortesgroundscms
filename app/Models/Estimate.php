<?php

namespace App\Models;

use BokshornIt\FilamentActivityTimeline\Contracts\ProvidesActivityTitle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Estimate extends Model implements ProvidesActivityTitle
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'project_id',
        'estimate_no',
        'grand_total',
        'status',
        'notes',
        'prepared_by',
    ];

    // public function estimateItems(): HasMany
    // {
    //     return $this->hasMany(EstimateItem::class);
    // }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(EstimateItem::class);
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function activityTitle(): ?string
    {
        return $this->title;
    }
}
