@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-yellow-200 text-gray-800 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold text-yellow-600">Verificar Tu Email</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @else
        <p class="mt-4">Antes de proceder, por favor verifica tu email para un enlace de verificación.</p>
        <p class="mt-2">Si no recibiste el email, <a href="{{ route('verification.send') }}" class="text-blue-600 hover:text-blue-800">haz clic aquí para solicitar otro</a>.</p>
    @endif
</div>
@endsection