<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'supply_center',
        'role',
        'hours_assigned',
        'schedule_date',
        'schedule_status',
        'image'
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'hours_assigned' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
