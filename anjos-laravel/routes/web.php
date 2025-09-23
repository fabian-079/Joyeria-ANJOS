<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\CustomizationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('inicio');
Route::get('/catalogo', [HomeController::class, 'catalogo'])->name('catalogo');
Route::get('/producto/{product}', [HomeController::class, 'producto'])->name('producto.show');
Route::get('/buscar', [App\Http\Controllers\SearchController::class, 'index'])->name('buscar');

// Autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Recuperación de contraseña
Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset'])->name('password.update');

// Rutas de registro
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.post');

// Rutas de verificación de correo electrónico
Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

// Rutas para usuarios autenticados
Route::middleware(['auth'])->group(function () {
    
    // Carrito y favoritos
    Route::get('/carrito', [CartController::class, 'index'])->name('carrito');
    Route::post('/carrito/checkout', [CartController::class, 'checkout'])->name('carrito.checkout');
    Route::put('/carrito/{cartItem}', [CartController::class, 'update'])->name('carrito.update');
    Route::delete('/carrito/{cartItem}', [CartController::class, 'remove'])->name('carrito.remove');
    
    Route::get('/favoritos', [FavoriteController::class, 'index'])->name('favoritos');
    Route::post('/favoritos/toggle', [FavoriteController::class, 'toggle'])->name('favoritos.toggle');
    Route::delete('/favoritos/{favorite}', [FavoriteController::class, 'remove'])->name('favoritos.remove');
    
    // Productos
    Route::post('/producto/{product}/carrito', [ProductController::class, 'addToCart'])->name('producto.carrito');
    Route::post('/producto/{product}/favoritos', [ProductController::class, 'addToFavorites'])->name('producto.favoritos');
    
    // Reparaciones
    Route::get('/reparaciones', [RepairController::class, 'index'])->name('reparaciones.index');
    Route::get('/reparaciones/create', [RepairController::class, 'create'])->name('reparaciones.create');
    Route::post('/reparaciones', [RepairController::class, 'store'])->name('reparaciones.store');
    Route::get('/reparaciones/{repair}', [RepairController::class, 'show'])->name('reparaciones.show');
    
    // Personalizaciones
    Route::get('/personalizacion', [CustomizationController::class, 'index'])->name('personalizacion.index');
    Route::get('/personalizacion/create', [CustomizationController::class, 'create'])->name('personalizacion.create');
    Route::post('/personalizacion', [CustomizationController::class, 'store'])->name('personalizacion.store');
    Route::get('/personalizacion/{customization}', [CustomizationController::class, 'show'])->name('personalizacion.show');
    Route::post('/personalizacion/{customization}/carrito', [CustomizationController::class, 'addToCart'])->name('personalizacion.carrito');
    Route::post('/personalizacion/{customization}/favoritos', [CustomizationController::class, 'addToFavorites'])->name('personalizacion.favoritos');
    
    // Órdenes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas para usuarios autenticados
Route::middleware(['auth'])->group(function () {
    // Dashboard - accesible para todos los usuarios autenticados
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Rutas solo para administradores
Route::middleware(['auth'])->group(function () {
    // Verificar rol de admin en el controlador
    Route::get('/dashboard/ventas', [DashboardController::class, 'sales'])->name('dashboard.sales');
    Route::get('/dashboard/stock', [DashboardController::class, 'stock'])->name('dashboard.stock');
    Route::get('/dashboard/servicios', [DashboardController::class, 'services'])->name('dashboard.services');
    Route::get('/dashboard/reportes', [DashboardController::class, 'reports'])->name('dashboard.reports');
    
    // Gestión de productos
    Route::resource('products', ProductController::class);
    
    // Gestión de reparaciones
    Route::get('/reparaciones/{repair}/edit', [RepairController::class, 'edit'])->name('reparaciones.edit');
    Route::put('/reparaciones/{repair}', [RepairController::class, 'update'])->name('reparaciones.update');
    Route::delete('/reparaciones/{repair}', [RepairController::class, 'destroy'])->name('reparaciones.destroy');
    Route::post('/reparaciones/{repair}/asignar', [RepairController::class, 'assignTechnician'])->name('reparaciones.asignar');
    
    // Gestión de personalizaciones
    Route::get('/personalizacion/{customization}/edit', [CustomizationController::class, 'edit'])->name('personalizacion.edit');
    Route::put('/personalizacion/{customization}', [CustomizationController::class, 'update'])->name('personalizacion.update');
    Route::delete('/personalizacion/{customization}', [CustomizationController::class, 'destroy'])->name('personalizacion.destroy');
    
    // Gestión de usuarios
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
});
