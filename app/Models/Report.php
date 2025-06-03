<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Report extends Model {
    protected $fillable = [
        'name',
        'description',
        'frequency',
        'scheduled_time',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'scheduled_time' => 'datetime',
    ];

    public function stakeholders(): BelongsToMany
    {
        return $this->belongsToMany(Stakeholder::class)
            ->withPivot('customizations')
            ->withTimestamps();
    }
}
