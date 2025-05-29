<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentLog extends Model
{
    protected $fillable = [
        'order_id',
        'from_location',
        'to_location',
        'shipment_date',
        'status'
    ];

    protected $casts = [
        'shipment_date' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
} 