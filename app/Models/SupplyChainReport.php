<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyChainReport extends Model
{
    protected $fillable = ['stakeholder_id', 'title', 'content', 'status'];

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }
} 