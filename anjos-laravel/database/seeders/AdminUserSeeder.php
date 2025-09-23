<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles si no existen
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $empleadoRole = Role::firstOrCreate(['name' => 'empleado']);

        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@anjos.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@anjos.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Asignar rol de administrador
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Crear usuario de prueba
        $user = User::firstOrCreate(
            ['email' => 'user@anjos.com'],
            [
                'name' => 'Usuario Prueba',
                'email' => 'user@anjos.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Asignar rol de usuario
        if (!$user->hasRole('user')) {
            $user->assignRole('user');
        }
    }
}
