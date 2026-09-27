<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_code',
        'first_name',
        'last_name',
        'position',
        'employee_type',
        'date_hired',
        'date_terminated',
        'basic_rate',
        'rate_type',
        'contact_no',
        'address',
        'status',
    ];

    protected $casts = [
        'date_hired' => 'date',
        'date_terminated' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scheduleTasks(): HasMany
    {
        return $this->hasMany(ScheduleTask::class);
    }



    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
