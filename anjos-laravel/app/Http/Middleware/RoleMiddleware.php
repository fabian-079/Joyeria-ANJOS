<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role = 'admin'): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Debug: verificar si el usuario tiene roles
        if (!$user->hasRole($role)) {
            abort(403, 'No tienes permisos para acceder a esta sección. Usuario: ' . $user->name . ', Roles: ' . $user->roles->pluck('name')->implode(', '));
        }

        return $next($request);
    }
}









