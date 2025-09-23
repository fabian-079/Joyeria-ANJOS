<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'material',
        'color',
        'finish',
        'stones',
        'image',
        'gallery',
        'is_featured',
        'is_active',
        'category_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function getImageUrlAttribute(): string
    {
        // Si es una URL externa (empieza con http)
        if ($this->image && str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        
        // Si es una imagen local almacenada
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        
        // Usar imágenes placeholder locales según el tipo de producto
        $placeholderImages = [
            'anillos' => asset('img/anillo-esmeralda.png'),
            'cadenas' => asset('img/ESCLAVASORO1491.jpg'),
            'relojes' => asset('img/Reloj-submarino-de-lujo-para-hombre-cron-grafo-de-la-serie-Water-Ghost-multifunci-n-movimiento.webp'),
            'pulseras' => asset('img/Pulsera.webp'),
            'aretes' => asset('img/Esmeralda.jpg'),
            'dijes' => asset('img/Dijes-del-arbol-de-la-vida-min.jpg')
        ];
        
        $categoryName = strtolower($this->category->name ?? 'anillos');
        return $placeholderImages[$categoryName] ?? asset('img/anillo-esmeralda.png');
    }

    public function getGalleryUrlsAttribute(): array
    {
        if (!$this->gallery) {
            return [];
        }
        
        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->gallery);
    }
}
