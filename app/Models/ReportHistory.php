<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportHistory extends Model
{
    protected $fillable = [
        'report_schedule_id',
        'stakeholder_id',
        'status',
        'content_path',
        'error_message',
        'format',
        'delivery_channels'
    ];

    protected $casts = [
        'delivery_channels' => 'array'
    ];

    public function reportSchedule(): BelongsTo
    {
        return $this->belongsTo(ReportSchedule::class);
    }

    public function stakeholder(): BelongsTo
    {
        return $this->belongsTo(Stakeholder::class);
    }
} 