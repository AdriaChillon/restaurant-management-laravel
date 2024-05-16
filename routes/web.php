<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BarraController;
use App\Http\Controllers\CamareroController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CocineroController;
use App\Http\Controllers\ComandaController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('/auth/login');
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


// Rutas accesibles por el admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    //Rutas para los roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::put('/roles/{user}/update', [RoleController::class, 'updateRoles'])->name('roles.update');
    
    // Rutas para Producto
    Route::resource('productos', ProductoController::class)->except(['show']);
    
    // Rutas para Mesa
    Route::resource('mesas', MesaController::class)->except(['show']);
    
    // Rutas para Categoria
    Route::resource('categorias', CategoriaController::class)->except(['show']);
    
    // Rutas para Comanda
    Route::resource('comandas', ComandaController::class)->except(['create', 'store', 'show']);

});

// Rutas para barra
Route::middleware(['auth', 'role:barra|admin'])->group(function () {
    Route::get('/barra', [BarraController::class, 'index'])->name('barra.index');
    Route::put('/barra/{id}/cobrar', [BarraController::class, 'cobrar'])->name('barra.cobrar');
});

// Rutas para cocina
Route::middleware(['auth', 'role:cocina|admin'])->group(function () {
    Route::get('/cocinero', [CocineroController::class, 'index'])->name('cocinero.index');
    Route::put('/cocinero/{id}/cambiarEstado', [CocineroController::class, 'cambiarEstado'])->name('cocinero.cambiarEstado');
    Route::post('/comanda-updated', [CocineroController::class, 'handleComandaUpdated'])->name('comanda.updated');
});

// Rutas para camarero
Route::middleware(['auth', 'role:camarero|admin'])->group(function () {
    Route::get('/camarero', [CamareroController::class, 'index'])->name('camarero.index');
    Route::get('/camarero/create', [CamareroController::class, 'create'])->name('camarero.create');
    Route::post('/camarero', [CamareroController::class, 'store'])->name('camarero.store');
    Route::get('/camarero/{id}/edit', [CamareroController::class, 'edit'])->name('camarero.edit');
    Route::put('/camarero/{id}', [CamareroController::class, 'update'])->name('camarero.update');
});

// Rutas accesibles por cualquier usuario autenticado
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
