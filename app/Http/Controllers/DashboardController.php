<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\User;
use App\Models\Producto;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Resumen general
        $totalVentas = Venta::count();
        $ventasHoy = Venta::whereDate('created_at', Carbon::today())->count();
        $totalClientes = User::count();        // Aquí usamos User
        $totalProductos = Producto::count();

        // Datos para gráficos por mes
        $meses = [];
        $ventasMensuales = [];
        $clientesMensuales = [];

        for ($i = 1; $i <= 12; $i++) {
            $meses[] = Carbon::create()->month($i)->format('F');
            $ventasMensuales[] = Venta::whereMonth('created_at', $i)->count();
            $clientesMensuales[] = User::whereMonth('created_at', $i)->count(); // User en lugar de Cliente
        }

        // Retornamos la vista con los datos
        return view('dashboard', compact(
            'totalVentas', 'ventasHoy', 'totalClientes', 'totalProductos',
            'meses', 'ventasMensuales', 'clientesMensuales'
        ));
    }
}
