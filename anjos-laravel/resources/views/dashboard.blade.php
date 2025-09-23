@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-yellow-200 text-gray-800 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-center text-yellow-600">Dashboard - Anjos Joyería</h1>
    <p class="text-lg text-center mt-2">Bienvenido, {{ auth()->user()->name }}!</p>
    <p class="text-md text-center mt-1">Rol: {{ auth()->user()->roles->pluck('name')->implode(', ') }}</p>

    @if(auth()->user()->hasRole('admin'))
        <h2 class="text-2xl font-semibold mt-6 text-yellow-700">Gestión de Usuarios</h2>
        <table class="min-w-full bg-white mt-4 rounded-lg shadow">
            <thead>
                <tr class="bg-yellow-600 text-white">
                    <th class="py-2 px-4">Nombre</th>
                    <th class="py-2 px-4">Email</th>
                    <th class="py-2 px-4">Rol</th>
                    <th class="py-2 px-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-b">
                        <td class="py-2 px-4">{{ $user->name }}</td>
                        <td class="py-2 px-4">{{ $user->email }}</td>
                        <td class="py-2 px-4">{{ $user->roles->pluck('name')->implode(', ') }}</td>
                        <td class="py-2 px-4">
                            <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800">Editar Rol</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="mt-6 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg">
            <p class="text-center font-medium">No tienes permisos de administrador para ver la gestión de usuarios.</p>
        </div>
    @endif

    <div class="flex justify-center mt-6 space-x-4">
        <a href="{{ route('joyerias.index') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Ir a Gestión de Joyería</a>
        <a href="{{ route('joyerias.report') }}" class="btn bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Generar Reporte</a>
    </div>
</div>
@endsection
