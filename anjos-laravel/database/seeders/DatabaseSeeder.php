<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Repair;
use App\Models\Customization;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear roles y permisos
        $this->createRolesAndPermissions();

        // Crear usuarios
        $this->createUsers();

        // Crear categorías
        $this->createCategories();

        // Crear productos
        $this->createProducts();

        // Crear pedidos
        $this->createOrders();

        // Crear reparaciones
        $this->createRepairs();

        // Crear personalizaciones
        $this->createCustomizations();
    }

    private function createRolesAndPermissions()
    {
        // Crear roles
        $adminRole = Role::create(['name' => 'admin']);
        $clientRole = Role::create(['name' => 'cliente']);

        // Crear permisos
        $permissions = [
            'manage-products',
            'manage-orders',
            'manage-users',
            'manage-repairs',
            'manage-customizations',
            'view-reports',
            'export-reports',
            'manage-categories',
            'view-dashboard'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Asignar permisos a roles
        $adminRole->givePermissionTo(Permission::all());
        $clientRole->givePermissionTo(['view-dashboard']);
    }

    private function createUsers()
    {
        // Admin
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@anjos.com',
            'password' => Hash::make('password123'),
            'phone' => '3132090475',
            'address' => 'CALLE 38C SUR #87D - 09 / BOGOTÁ, COLOMBIA',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');


        // Clientes
        for ($i = 1; $i <= 10; $i++) {
            $client = User::create([
                'name' => "Cliente {$i}",
                'email' => "cliente{$i}@example.com",
                'password' => Hash::make('password123'),
                'phone' => '300' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'address' => "Dirección {$i}, Bogotá, Colombia",
                'email_verified_at' => now(),
            ]);
            $client->assignRole('cliente');
        }
    }

    private function createCategories()
    {
        $categories = [
            ['name' => 'Anillos', 'description' => 'Anillos de compromiso, bodas y moda'],
            ['name' => 'Cadenas', 'description' => 'Cadenas de oro y plata'],
            ['name' => 'Relojes', 'description' => 'Relojes de lujo para hombre y mujer'],
            ['name' => 'Pulseras', 'description' => 'Pulseras elegantes y modernas'],
            ['name' => 'Aretes', 'description' => 'Aretes de diferentes estilos'],
            ['name' => 'Dijes', 'description' => 'Dijes personalizados y únicos'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }

    private function createProducts()
    {
        $products = [
            [
                'name' => 'Anillo de Compromiso Esmeralda',
                'description' => 'Anillo de compromiso con esmeralda colombiana de 1 quilate, diseño clásico y elegante',
                'price' => 2500000,
                'stock' => 5,
                'material' => 'Oro 18k',
                'color' => 'Dorado',
                'finish' => 'Brillante',
                'stones' => 'Esmeralda',
                'image' => 'anillo-esmeralda.png',
                'is_featured' => true,
                'category_id' => 1
            ],
            [
                'name' => 'Anillo de Diamante Solitario',
                'description' => 'Anillo de compromiso con diamante solitario de 0.5 quilates en montura de oro blanco',
                'price' => 1800000,
                'stock' => 8,
                'material' => 'Oro 18k',
                'color' => 'Blanco',
                'finish' => 'Brillante',
                'stones' => 'Diamante',
                'image' => 'anillo-diamante.jpg',
                'is_featured' => true,
                'category_id' => 1
            ],
            [
                'name' => 'Anillo de Rubí y Diamantes',
                'description' => 'Anillo elegante con rubí central rodeado de diamantes pequeños',
                'price' => 3200000,
                'stock' => 3,
                'material' => 'Oro 18k',
                'color' => 'Dorado',
                'finish' => 'Brillante',
                'stones' => 'Rubí y Diamante',
                'image' => 'anillo-rubi.jpg',
                'is_featured' => true,
                'category_id' => 1
            ],
            [
                'name' => 'Anillo de Perla Cultivada',
                'description' => 'Anillo delicado con perla cultivada en montura de oro amarillo',
                'price' => 950000,
                'stock' => 12,
                'material' => 'Oro 14k',
                'color' => 'Dorado',
                'finish' => 'Brillante',
                'stones' => 'Perla',
                'image' => 'anillo-perla.jpg',
                'is_featured' => true,
                'category_id' => 1
            ],
            [
                'name' => 'Cadena de Oro Italiana',
                'description' => 'Cadena de oro italiano de 18k, 50cm',
                'price' => 1800000,
                'stock' => 8,
                'material' => 'Oro 18k',
                'color' => 'Dorado',
                'finish' => 'Brillante',
                'stones' => 'Ninguna',
                'image' => 'cadena-oro.jpg',
                'is_featured' => true,
                'category_id' => 2
            ],
            [
                'name' => 'Reloj Submarino de Lujo',
                'description' => 'Reloj submarino multifunción con cronógrafo',
                'price' => 3500000,
                'stock' => 3,
                'material' => 'Acero inoxidable',
                'color' => 'Plateado',
                'finish' => 'Mate',
                'stones' => 'Ninguna',
                'image' => 'reloj-submarino.webp',
                'is_featured' => true,
                'category_id' => 3
            ],
            [
                'name' => 'Pulsera de Plata Sterling',
                'description' => 'Pulsera de plata sterling con diseño moderno',
                'price' => 450000,
                'stock' => 12,
                'material' => 'Plata Sterling',
                'color' => 'Plateado',
                'finish' => 'Brillante',
                'stones' => 'Ninguna',
                'image' => 'pulsera-plata.jpg',
                'is_featured' => false,
                'category_id' => 4
            ],
            [
                'name' => 'Aretes de Diamante',
                'description' => 'Aretes con diamantes pequeños, perfectos para ocasiones especiales',
                'price' => 1200000,
                'stock' => 6,
                'material' => 'Oro 14k',
                'color' => 'Dorado',
                'finish' => 'Brillante',
                'stones' => 'Diamante',
                'image' => 'aretes-diamante.jpg',
                'is_featured' => true,
                'category_id' => 5
            ],
            [
                'name' => 'Dije Árbol de la Vida',
                'description' => 'Dije del árbol de la vida en plata con detalles dorados',
                'price' => 320000,
                'stock' => 15,
                'material' => 'Plata y Oro',
                'color' => 'Bicolor',
                'finish' => 'Brillante',
                'stones' => 'Ninguna',
                'image' => 'dije-arbol.jpg',
                'is_featured' => false,
                'category_id' => 6
            ],
            [
                'name' => 'Anillo de Boda Clásico',
                'description' => 'Anillo de boda clásico en oro amarillo',
                'price' => 800000,
                'stock' => 10,
                'material' => 'Oro 18k',
                'color' => 'Dorado',
                'finish' => 'Brillante',
                'stones' => 'Ninguna',
                'image' => 'anillo-boda.jpg',
                'is_featured' => false,
                'category_id' => 1
            ],
            [
                'name' => 'Cadena de Plata',
                'description' => 'Cadena de plata sterling estilo figaro',
                'price' => 280000,
                'stock' => 20,
                'material' => 'Plata Sterling',
                'color' => 'Plateado',
                'finish' => 'Brillante',
                'stones' => 'Ninguna',
                'image' => 'cadena-plata.jpg',
                'is_featured' => false,
                'category_id' => 2
            ],
            [
                'name' => 'Reloj Elegante Mujer',
                'description' => 'Reloj elegante para mujer con correa de cuero',
                'price' => 950000,
                'stock' => 7,
                'material' => 'Acero inoxidable',
                'color' => 'Dorado',
                'finish' => 'Brillante',
                'stones' => 'Cristales',
                'image' => 'reloj-mujer.jpg',
                'is_featured' => true,
                'category_id' => 3
            ],
            [
                'name' => 'Pulsera de Oro Rosa',
                'description' => 'Pulsera de oro rosa con diseño delicado',
                'price' => 650000,
                'stock' => 9,
                'material' => 'Oro 14k',
                'color' => 'Rosa',
                'finish' => 'Brillante',
                'stones' => 'Ninguna',
                'image' => 'pulsera-rosa.jpg',
                'is_featured' => true,
                'category_id' => 4
            ],
            [
                'name' => 'Cadena de Plata Sterling',
                'description' => 'Cadena de plata sterling estilo figaro con cierre seguro',
                'price' => 280000,
                'stock' => 20,
                'material' => 'Plata Sterling',
                'color' => 'Plateado',
                'finish' => 'Brillante',
                'stones' => 'Ninguna',
                'image' => 'cadena-plata.jpg',
                'is_featured' => true,
                'category_id' => 2
            ],
            [
                'name' => 'Reloj Elegante Mujer',
                'description' => 'Reloj elegante para mujer con correa de cuero genuino',
                'price' => 950000,
                'stock' => 7,
                'material' => 'Acero inoxidable',
                'color' => 'Dorado',
                'finish' => 'Brillante',
                'stones' => 'Cristales',
                'image' => 'reloj-mujer.jpg',
                'is_featured' => true,
                'category_id' => 3
            ],
            [
                'name' => 'Aretes de Diamante',
                'description' => 'Aretes con diamantes pequeños, perfectos para ocasiones especiales',
                'price' => 1200000,
                'stock' => 6,
                'material' => 'Oro 14k',
                'color' => 'Dorado',
                'finish' => 'Brillante',
                'stones' => 'Diamante',
                'image' => 'aretes-diamante.jpg',
                'is_featured' => true,
                'category_id' => 5
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }

    private function createOrders()
    {
        $users = User::whereHas('roles', function($query) {
            $query->where('name', 'cliente');
        })->take(5)->get();

        foreach ($users as $user) {
            for ($i = 0; $i < 2; $i++) {
                $order = Order::create([
                    'order_number' => 'ORD-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'user_id' => $user->id,
                    'subtotal' => rand(500000, 3000000),
                    'tax' => rand(50000, 300000),
                    'total' => rand(550000, 3300000),
                    'status' => ['pending', 'processing', 'shipped', 'delivered'][rand(0, 3)],
                    'shipping_address' => $user->address,
                    'billing_address' => $user->address,
                    'phone' => $user->phone,
                    'notes' => 'Pedido especial',
                ]);

                // Crear items del pedido
                $products = Product::inRandomOrder()->take(rand(1, 3))->get();
                foreach ($products as $product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => rand(1, 2),
                        'price' => $product->price,
                    ]);
                }
            }
        }
    }

    private function createRepairs()
    {
        $users = User::whereHas('roles', function($query) {
            $query->where('name', 'cliente');
        })->take(8)->get();

        foreach ($users as $user) {
            Repair::create([
                'repair_number' => 'REP-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'description' => 'Reparación de ' . ['anillo', 'cadena', 'reloj', 'pulsera'][rand(0, 3)] . ' - ' . ['engaste', 'soldadura', 'pulido', 'cambio de batería'][rand(0, 3)],
                'phone' => $user->phone,
                'status' => ['pending', 'in_progress', 'completed'][rand(0, 2)],
                'estimated_cost' => rand(50000, 500000),
                'technician_notes' => 'Trabajo en progreso',
            ]);
        }
    }

    private function createCustomizations()
    {
        $users = User::whereHas('roles', function($query) {
            $query->where('name', 'cliente');
        })->take(6)->get();

        foreach ($users as $user) {
            Customization::create([
                'user_id' => $user->id,
                'jewelry_type' => ['anillo', 'cadena', 'pulsera', 'aretes'][rand(0, 3)],
                'design' => ['clásico', 'moderno', 'vintage', 'minimalista'][rand(0, 3)],
                'stones' => ['diamante', 'esmeralda', 'rubí', 'ninguna'][rand(0, 3)],
                'finish' => ['brillante', 'mate', 'satín'][rand(0, 2)],
                'color' => ['dorado', 'plateado', 'rosa', 'blanco'][rand(0, 3)],
                'material' => ['oro 18k', 'oro 14k', 'plata sterling', 'acero'][rand(0, 3)],
                'engraving' => 'Iniciales personalizadas',
                'special_instructions' => 'Diseño único y personalizado',
                'estimated_price' => rand(800000, 4000000),
                'status' => ['pending', 'in_progress', 'completed'][rand(0, 2)],
            ]);
        }
    }
}