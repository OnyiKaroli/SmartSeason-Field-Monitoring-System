<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    /** @use HasFactory<\Database\Factories\FieldFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'crop_type',
        'planting_date',
        'current_stage',
        'assigned_agent_id',
        'created_by',
    ];

    protected $casts = [
        'planting_date' => 'date',
    ];

    public const STAGES = [
        'Planted',
        'Growing',
        'Ready',
        'Harvested',
    ];

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updates()
    {
        return $this->hasMany(FieldUpdate::class)->latest();
    }

    public function getStatusAttribute(): string
    {
        return app(\App\Services\FieldStatusService::class)->getStatus($this);
    }

    public function getStatusReasonAttribute(): string
    {
        return app(\App\Services\FieldStatusService::class)->getStatusReason($this);
    }
}
