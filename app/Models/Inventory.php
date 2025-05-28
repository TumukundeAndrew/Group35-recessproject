<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model {
    protected $fillable = ['product_id', 'quantity', 'reorder_level', 'location'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
