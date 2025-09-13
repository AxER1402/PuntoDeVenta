<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return redirect()->route('cotizaciones.index');
});

Route::resource('cotizaciones', CotizacionController::class);

Route::resource('productos', ProductoController::class);

Route::get('clientes/search', [ClienteController::class, 'search'])->name('clientes.search');
Route::resource('clientes', ClienteController::class);

Route::post('cotizaciones/{id}/estado/{estadoId}', [CotizacionController::class, 'cambiarEstado']);
Route::get('/cotizaciones', [CotizacionController::class, 'index'])->name('cotizaciones.index');
Route::get('/cotizaciones/create', [CotizacionController::class, 'create'])->name('cotizaciones.create');
Route::post('/cotizaciones', [CotizacionController::class, 'store'])->name('cotizaciones.store');
Route::get('/cotizaciones/{id}', [CotizacionController::class, 'show'])->name('cotizaciones.show');
Route::post('/cotizaciones/{id}/estado/{estadoId}', [CotizacionController::class, 'cambiarEstado'])->name('cotizaciones.estado');

