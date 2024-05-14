<?php

use App\Http\Controllers\BarraController;
use App\Http\Controllers\CamareroController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CocineroController;
use App\Http\Controllers\ComandaController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('/auth/login');
});
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        if(Auth::check()) {
            $user = Auth::user();
            if($user->name === 'admin') {
                return redirect()->route('productos.index');
            } else {
                return redirect()->route('camarero.index');
            }
        }
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Rutas para Producto
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');

// Rutas para Mesa
Route::get('/mesas', [MesaController::class, 'index'])->name('mesas.index');
Route::get('/mesas/create', [MesaController::class, 'create'])->name('mesas.create');
Route::post('/mesas', [MesaController::class, 'store'])->name('mesas.store');
Route::get('/mesas/{mesa}/edit', [MesaController::class, 'edit'])->name('mesas.edit');
Route::put('/mesas/{mesa}', [MesaController::class, 'update'])->name('mesas.update');
Route::delete('/mesas/{mesa}', [MesaController::class, 'destroy'])->name('mesas.destroy');

// Rutas para Categoria
Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
Route::get('/categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');
Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
Route::get('/categorias/{categoria}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit');
Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

// Rutas para Comanda la de crear no esta,ya que no tiene sentido que la cree el jefe si no hay ningun pedido real
Route::get('/comandas', [ComandaController::class, 'index'])->name('comandas.index');
Route::get('/comandas/{comanda}/edit', [ComandaController::class, 'edit'])->name('comandas.edit');
Route::put('/comandas/{comanda}', [ComandaController::class, 'update'])->name('comandas.update');
Route::delete('/comandas/{comanda}', [ComandaController::class, 'destroy'])->name('comandas.destroy');

Route::get('/camarero', [CamareroController::class, 'index'])->name('camarero.index');
Route::get('/camarero/create', [CamareroController::class, 'create'])->name('camarero.create');
Route::post('/camarero', [CamareroController::class, 'store'])->name('camarero.store');
Route::get('/camarero/{id}/edit', [CamareroController::class, 'edit'])->name('camarero.edit');
Route::put('/camarero/{id}', [CamareroController::class, 'update'])->name('camarero.update');

Route::get('/cocinero', [CocineroController::class, 'index'])->name('cocinero.index');
Route::put('/cocinero/{id}/cambiarEstado', [CocineroController::class, 'cambiarEstado'])->name('cocinero.cambiarEstado');

// Ruta para escuchar eventos ComandaUpdated
Route::post('/comanda-updated', [CocineroController::class, 'handleComandaUpdated'])->name('comanda.updated');


Route::get('/barra', [BarraController::class, 'index'])->name('barra.index');
Route::put('/barra/{id}/cobrar', [BarraController::class, 'cobrar'])->name('barra.cobrar');
