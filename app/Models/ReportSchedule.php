<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReportSchedule extends Model
{
    protected $fillable = [
        'name',
        'description',
        'frequency',
        'scheduled_time',
        'type',
        'is_active'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * The stakeholders that receive this report.
     */
    public function stakeholders(): BelongsToMany
    {
        return $this->belongsToMany(Stakeholder::class, 'report_schedule_stakeholder')
            ->withPivot('customizations')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active schedules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include schedules of a specific type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include schedules with a specific frequency.
     */
    public function scopeWithFrequency($query, string $frequency)
    {
        return $query->where('frequency', $frequency);
    }
} 