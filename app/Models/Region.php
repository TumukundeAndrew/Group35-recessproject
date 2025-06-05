<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = [
        'name',
        'code',
        'country',
        'timezone',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function demandForecasts(): HasMany
    {
        return $this->hasMany(DemandForecast::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sales::class);
    }
} 