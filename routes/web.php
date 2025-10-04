<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DashboardController;

// Redirigir al listado de productos por defecto
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rutas para Cotizaciones
Route::resource('cotizaciones', CotizacionController::class);
Route::post('/cotizaciones/{id}/estado/{estadoId}', [CotizacionController::class, 'cambiarEstado'])->name('cotizaciones.estado');

// Rutas para Productos
Route::resource('productos', ProductoController::class);
Route::get('/catalogo', [ProductoController::class, 'catalogo'])->name('productos.catalogo');

// Rutas para Clientes
Route::get('clientes/search', [ClienteController::class, 'search'])->name('clientes.search');
Route::resource('clientes', ClienteController::class);

// Rutas para Carrito
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/agregar/{id}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::post('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::post('/carrito/generar', [CarritoController::class, 'generarVenta'])->name('carrito.generar'); // quitar {id}

// Rutas para Ventas
Route::resource('ventas', VentaController::class)->only(['index', 'show']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');