<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sales extends Model
{
    protected $fillable = [
        'region_id',
        'product_id',
        'volume',
        'revenue',
        'date',
        'growth'
    ];

    protected $casts = [
        'volume' => 'float',
        'revenue' => 'decimal:2',
        'date' => 'datetime',
        'growth' => 'float'
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function byRegion($regionId)
    {
        return static::where('region_id', $regionId)
            ->where('date', '>=', now()->startOfMonth())
            ->where('date', '<=', now()->endOfMonth());
    }
} 