<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_number',
        'user_id',
        'customer_name',
        'description',
        'phone',
        'image',
        'status',
        'assigned_technician_id',
        'estimated_cost',
        'technician_notes',
        'notes'
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTechnician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }

    public function getFormattedEstimatedCostAttribute(): string
    {
        return $this->estimated_cost ? '$' . number_format($this->estimated_cost, 0, ',', '.') : 'Por cotizar';
    }
}

