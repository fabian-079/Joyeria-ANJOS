<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductImageSeeder extends Seeder
{
    public function run()
    {
        // Crear categorías si no existen
        $categories = [
            ['name' => 'Anillos', 'description' => 'Anillos de compromiso, bodas y moda', 'is_active' => true],
            ['name' => 'Cadenas', 'description' => 'Cadenas de oro y plata', 'is_active' => true],
            ['name' => 'Relojes', 'description' => 'Relojes de lujo para hombre y mujer', 'is_active' => true],
            ['name' => 'Pulseras', 'description' => 'Pulseras elegantes y modernas', 'is_active' => true],
            ['name' => 'Aretes', 'description' => 'Aretes y pendientes de diseño', 'is_active' => true],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );
        }

        // Productos de ejemplo con imágenes
        $products = [
            [
                'name' => 'Anillo de Compromiso Diamante',
                'description' => 'Hermoso anillo de compromiso con diamante central de 1 quilate, montura en oro blanco 18k.',
                'price' => 2500000,
                'category_name' => 'Anillos',
                'material' => 'Oro Blanco 18k',
                'color' => 'Plateado',
                'finish' => 'Brillante',
                'stones' => 'Diamante 1ct',
                'stock' => 5,
                'is_featured' => true,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=500&h=500&fit=crop'
            ],
            [
                'name' => 'Cadena de Oro 18k',
                'description' => 'Cadena de oro amarillo 18k, eslabones redondos, longitud 50cm.',
                'price' => 800000,
                'category_name' => 'Cadenas',
                'material' => 'Oro Amarillo 18k',
                'color' => 'Dorado',
                'finish' => 'Brillante',
                'stones' => 'Ninguna',
                'stock' => 10,
                'is_featured' => true,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=500&h=500&fit=crop'
            ],
            [
                'name' => 'Reloj Suizo de Lujo',
                'description' => 'Reloj suizo automático con caja de acero inoxidable y correa de cuero genuino.',
                'price' => 3500000,
                'category_name' => 'Relojes',
                'material' => 'Acero Inoxidable',
                'color' => 'Plateado',
                'finish' => 'Pulido',
                'stones' => 'Ninguna',
                'stock' => 3,
                'is_featured' => true,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1523170335258-f5e6c7c4e4c0?w=500&h=500&fit=crop'
            ],
            [
                'name' => 'Pulsera de Plata 925',
                'description' => 'Pulsera de plata 925 con diseño moderno y acabado brillante.',
                'price' => 350000,
                'category_name' => 'Pulseras',
                'material' => 'Plata 925',
                'color' => 'Plateado',
                'finish' => 'Brillante',
                'stones' => 'Ninguna',
                'stock' => 15,
                'is_featured' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=500&h=500&fit=crop'
            ],
            [
                'name' => 'Aretes de Perlas',
                'description' => 'Aretes elegantes con perlas naturales y montura en oro amarillo 14k.',
                'price' => 450000,
                'category_name' => 'Aretes',
                'material' => 'Oro Amarillo 14k',
                'color' => 'Dorado',
                'finish' => 'Brillante',
                'stones' => 'Perlas Naturales',
                'stock' => 8,
                'is_featured' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=500&h=500&fit=crop'
            ],
            [
                'name' => 'Anillo de Bodas Clásico',
                'description' => 'Anillo de bodas en oro amarillo 18k con diseño clásico y acabado satinado.',
                'price' => 600000,
                'category_name' => 'Anillos',
                'material' => 'Oro Amarillo 18k',
                'color' => 'Dorado',
                'finish' => 'Satinado',
                'stones' => 'Ninguna',
                'stock' => 12,
                'is_featured' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=500&h=500&fit=crop'
            ],
            [
                'name' => 'Cadena de Plata 925',
                'description' => 'Cadena de plata 925 con eslabones ovalados, longitud 45cm.',
                'price' => 250000,
                'category_name' => 'Cadenas',
                'material' => 'Plata 925',
                'color' => 'Plateado',
                'finish' => 'Brillante',
                'stones' => 'Ninguna',
                'stock' => 20,
                'is_featured' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=500&h=500&fit=crop'
            ],
            [
                'name' => 'Reloj Deportivo',
                'description' => 'Reloj deportivo resistente al agua con cronómetro y pantalla digital.',
                'price' => 1200000,
                'category_name' => 'Relojes',
                'material' => 'Acero Inoxidable',
                'color' => 'Negro',
                'finish' => 'Mate',
                'stones' => 'Ninguna',
                'stock' => 6,
                'is_featured' => false,
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1523170335258-f5e6c7c4e4c0?w=500&h=500&fit=crop'
            ]
        ];

        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category_name'])->first();
            
            if ($category) {
                $product = Product::create([
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'category_id' => $category->id,
                    'material' => $productData['material'],
                    'color' => $productData['color'],
                    'finish' => $productData['finish'],
                    'stones' => $productData['stones'],
                    'stock' => $productData['stock'],
                    'is_featured' => $productData['is_featured'],
                    'is_active' => $productData['is_active'],
                    'image' => $productData['image_url'] // Guardamos la URL de la imagen
                ]);
            }
        }
    }
}




