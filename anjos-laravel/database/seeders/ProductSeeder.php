<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear categorías si no existen
        $categories = [
            ['name' => 'Anillos', 'description' => 'Anillos de compromiso, bodas y moda', 'is_active' => true],
            ['name' => 'Cadenas', 'description' => 'Cadenas de oro y plata', 'is_active' => true],
            ['name' => 'Relojes', 'description' => 'Relojes de lujo y moda', 'is_active' => true],
            ['name' => 'Pulseras', 'description' => 'Pulseras y brazaletes', 'is_active' => true],
            ['name' => 'Aretes', 'description' => 'Aretes y pendientes', 'is_active' => true],
            ['name' => 'Dijes', 'description' => 'Dijes y colgantes', 'is_active' => true],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );
        }

        // Obtener categorías
        $anillos = Category::where('name', 'Anillos')->first();
        $cadenas = Category::where('name', 'Cadenas')->first();
        $relojes = Category::where('name', 'Relojes')->first();
        $pulseras = Category::where('name', 'Pulseras')->first();
        $aretes = Category::where('name', 'Aretes')->first();
        $dijes = Category::where('name', 'Dijes')->first();

        // Productos de ejemplo
        $products = [
            [
                'name' => 'Anillo de Compromiso Esmeralda',
                'description' => 'Hermoso anillo de compromiso con esmeralda colombiana de 1 quilate, montado en oro 18k. Una pieza única que simboliza el amor eterno.',
                'price' => 2500000,
                'stock' => 5,
                'material' => 'Oro 18k',
                'color' => 'Dorado',
                'finish' => 'Brillante',
                'stones' => 'Esmeralda 1ct',
                'category_id' => $anillos->id,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Cadena de Oro Italiana',
                'description' => 'Elegante cadena de oro italiano de 18k con eslabones entrelazados. Perfecta para uso diario o ocasiones especiales.',
                'price' => 1800000,
                'stock' => 8,
                'material' => 'Oro 18k',
                'color' => 'Dorado',
                'finish' => 'Brillante',
                'stones' => 'Ninguna',
                'category_id' => $cadenas->id,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Reloj Submarino de Lujo',
                'description' => 'Reloj submarino de alta gama con cronógrafo, resistente al agua hasta 200m. Movimiento automático suizo.',
                'price' => 4500000,
                'stock' => 3,
                'material' => 'Acero inoxidable',
                'color' => 'Plateado',
                'finish' => 'Pulido',
                'stones' => 'Zafiro',
                'category_id' => $relojes->id,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Pulsera de Plata Sterling',
                'description' => 'Delicada pulsera de plata sterling 925 con diseño minimalista. Ideal para complementar cualquier outfit.',
                'price' => 350000,
                'stock' => 12,
                'material' => 'Plata Sterling 925',
                'color' => 'Plateado',
                'finish' => 'Mate',
                'stones' => 'Ninguna',
                'category_id' => $pulseras->id,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Aretes de Esmeralda',
                'description' => 'Elegantes aretes con esmeraldas colombianas montadas en oro 14k. Un toque de sofisticación para cualquier ocasión.',
                'price' => 1200000,
                'stock' => 6,
                'material' => 'Oro 14k',
                'color' => 'Dorado',
                'finish' => 'Brillante',
                'stones' => 'Esmeralda 0.5ct',
                'category_id' => $aretes->id,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Dije del Árbol de la Vida',
                'description' => 'Simbolico dije del árbol de la vida en plata sterling con detalles artesanales. Representa crecimiento y conexión.',
                'price' => 280000,
                'stock' => 15,
                'material' => 'Plata Sterling 925',
                'color' => 'Plateado',
                'finish' => 'Antiguo',
                'stones' => 'Ninguna',
                'category_id' => $dijes->id,
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['name' => $productData['name']],
                $productData
            );
        }
    }
}
