<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    /**
     * Muestra una lista paginada de todas las ventas.
     */
    public function index()
    {
        // Obtenemos las ventas ordenadas por fecha de creación descendente
        $ventas = Venta::orderBy('created_at', 'desc')->paginate(15);
        return view('ventas.index', compact('ventas'));
    }

    /**
     * Muestra los detalles de una venta específica.
     */
    public function show(Venta $venta)
    {
        // Cargamos las relaciones 'detalles' y, para cada detalle, el 'producto' asociado
        $venta->load('detalles.producto');
        return view('ventas.show', compact('venta'));
    }
}
