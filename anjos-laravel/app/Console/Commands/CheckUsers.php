<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUsers extends Command
{
    protected $signature = 'check:users';
    protected $description = 'Verificar usuarios y sus roles';

    public function handle()
    {
        $this->info('Verificando usuarios y roles:');
        
        $users = User::with('roles')->get();
        
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->join(', ');
            $this->line("Usuario: {$user->email} - Roles: {$roles}");
        }
        
        return 0;
    }
}









