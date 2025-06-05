<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SupplyChainReport;

class Stakeholder extends Model
{
    protected $fillable = [
        'name',
        'email',
        'type',
        'contact_person',
        'phone',
        'address',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * The report schedules that this stakeholder receives.
     */
    public function reportSchedules(): BelongsToMany
    {
        return $this->belongsToMany(ReportSchedule::class, 'report_schedule_stakeholder')
            ->withPivot('customizations')
            ->withTimestamps();
    }

    /**
     * The invoices associated with this stakeholder.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Scope a query to only include active stakeholders.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include stakeholders of a specific type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function supplyChainReports()
    {
        return $this->hasMany(SupplyChainReport::class);
    }
}

