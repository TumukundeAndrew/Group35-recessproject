<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'status',
        'total_amount',
        'order_type',
        'customer_id',
        'retailer_id',
        'wholesaler_id',
        'vendor_id'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'quantity' => 'integer',
        'status' => 'string',
        'order_type' => 'string'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function retailer() {
        return $this->belongsTo(User::class, 'retailer_id');
    }

    public function wholesaler() {
        return $this->belongsTo(User::class, 'wholesaler_id');
    }

    public function vendor() {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function paymentTransactions() {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function shipmentLogs() {
        return $this->hasMany(ShipmentLog::class);
    }

    public function vendorOrders() {
        return $this->whereHas('user', function ($query) {
            $query->where('role', 'vendor');
        });
    }

    public function getOrderTypeLabel() {
        switch ($this->order_type) {
            case 'customer_to_retailer':
                return 'Customer → Retailer';
            case 'retailer_to_wholesaler':
                return 'Retailer → Wholesaler';
            case 'wholesaler_to_vendor':
                return 'Wholesaler → Vendor';
            default:
                return ucfirst(str_replace('_', ' ', $this->order_type));
        }
    }
}
