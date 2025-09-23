<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jewelry_type',
        'design',
        'stones',
        'finish',
        'color',
        'material',
        'engraving',
        'special_instructions',
        'estimated_price',
        'status',
        'admin_notes'
    ];

    protected $casts = [
        'estimated_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function getFormattedEstimatedPriceAttribute(): string
    {
        return $this->estimated_price ? '$' . number_format($this->estimated_price, 0, ',', '.') : 'Por cotizar';
    }
}

