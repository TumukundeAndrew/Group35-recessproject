<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'financial_stability',
        'reputation_score',
        'regulatory_compliance',
        'pdf_path',
        'application_status',
        'visit_date',
        'visit_status'
    ];

    protected $casts = [
        'financial_stability' => 'decimal:2',
        'regulatory_compliance' => 'boolean',
        'visit_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders() {
        return $this->hasMany(Order::class, 'user_id');
    }
}
