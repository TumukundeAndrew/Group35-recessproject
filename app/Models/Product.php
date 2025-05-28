<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $fillable = ['name', 'description', 'unit', 'category'];

    public function inventories() {
        return $this->hasMany(Inventory::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function sales() {
        return $this->hasMany(Sale::class);
    }
}
