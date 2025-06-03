<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'number',
        'region_id',
        'stakeholder_id',
        'amount',
        'status',
        'due_date',
        'paid_date',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'datetime',
        'paid_date' => 'datetime'
    ];

    /**
     * Get the stakeholder that owns the invoice.
     */
    public function stakeholder(): BelongsTo
    {
        return $this->belongsTo(Stakeholder::class);
    }

    /**
     * Get the region associated with the invoice.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Scope a query to only include pending invoices.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include paid invoices.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope a query to only include overdue invoices.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
            ->where('due_date', '<', now());
    }
} 