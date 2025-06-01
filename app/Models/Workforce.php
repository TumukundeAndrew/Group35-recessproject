<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workforce extends Model {
    protected $fillable = ['user_id', 'supply_center', 'role', 'hours_assigned', 'schedule_date', 'schedule_status'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
