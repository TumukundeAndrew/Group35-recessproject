<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemandForecast extends Model
{
    protected $fillable = [
        'date',
        'quantity',
        'product_id',
        'region_id',
        'confidence_score',
        'notes'
    ];

    protected $casts = [
        'date' => 'datetime',
        'quantity' => 'float',
        'confidence_score' => 'float'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
} 