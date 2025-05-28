<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {
    protected $fillable = ['name', 'contact_info'];

    public function orders() {
        return $this->hasMany(Order::class, 'user_id');
    }
}
