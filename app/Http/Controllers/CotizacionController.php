<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cotizacion;
use App\Models\CotizacionLinea;
use App\Models\Auditoria;
use App\Models\Producto;
use App\Models\User;
use App\Models\EstadoFactura;

class CotizacionController extends Controller
{
    /**
     * Crear una cotización con sus detalles
     */
    public function store(Request $request)
    {
        $cotizacion = null;
        
        DB::transaction(function () use ($request, &$cotizacion) {
            $cotizacion = Cotizacion::create([
                'cliente_id'   => $request->cliente_id,
                'usuario_crea' => auth()->user()->name ?? 'sistema',
            ]);

            $total = 0;
            foreach ($request->lineas as $linea) {
                $subtotal = $linea['cantidad'] * $linea['precio_unitario'];

                CotizacionLinea::create([
                    'cotizacion_id'   => $cotizacion->cotizacion_id,
                    'producto_id'     => $linea['producto_id'],
                    'cantidad'        => $linea['cantidad'],
                    'precio_unitario' => $linea['precio_unitario'],
                    'subtotal'        => $subtotal,
                ]);

                $total += $subtotal;
            }

            $cotizacion->update(['total' => $total]);

            Auditoria::create([
                'tabla'      => 'cotizaciones',
                'accion'     => 'INSERT',
                'registro_id'=> $cotizacion->cotizacion_id,
                'usuario'    => auth()->user()->name ?? 'sistema',
                'detalle'    => 'Cotización creada con detalles',
            ]);
        });

        return redirect()->route('cotizaciones.index')
                        ->with('success', 'Cotización creada exitosamente. ID: ' . $cotizacion->cotizacion_id);
    }

    /**
     * Cambiar el estado de una cotización con validaciones
     */
    public function cambiarEstado($id, $estadoId)
    {
        $cotizacion = Cotizacion::with('estado')->findOrFail($id);
        $nuevoEstado = EstadoFactura::findOrFail($estadoId);
        
        // Validar transiciones de estado
        $transicionesValidas = $this->getTransicionesValidas($cotizacion->estado_id);
        
        if (!in_array($estadoId, $transicionesValidas)) {
            return redirect()->back()->with('error', 
                "No se puede cambiar de '{$cotizacion->estado->nombre_estado}' a '{$nuevoEstado->nombre_estado}'. Transición no válida.");
        }

        // Validaciones específicas por estado
        if ($estadoId == 3 && $cotizacion->total <= 0) {
            return redirect()->back()->with('error', 'No se puede marcar como vendido una cotización sin productos.');
        }

        if ($estadoId == 4 && $cotizacion->estado_id != 3) {
            return redirect()->back()->with('error', 'Solo se puede facturar electrónicamente una cotización que esté en estado "Vendido".');
        }

        DB::transaction(function () use ($cotizacion, $estadoId, $nuevoEstado) {
            $estadoAnterior = $cotizacion->estado->nombre_estado;
            $cotizacion->update(['estado_id' => $estadoId]);

            Auditoria::create([
                'tabla'      => 'cotizaciones',
                'accion'     => 'UPDATE',
                'registro_id'=> $cotizacion->cotizacion_id,
                'usuario'    => auth()->user()->name ?? 'sistema',
                'detalle'    => "Estado cambiado de '{$estadoAnterior}' a '{$nuevoEstado->nombre_estado}'",
            ]);
        });

        return redirect()->route('cotizaciones.show', $cotizacion->cotizacion_id)
                         ->with('success', "Estado actualizado a '{$nuevoEstado->nombre_estado}' correctamente");
    }

    /**
     * Obtener transiciones válidas de estado
     */
    private function getTransicionesValidas($estadoActual)
    {
        $transiciones = [
            1 => [2, 3], // Cotización -> Anulado, Vendido
            2 => [1],    // Anulado -> Cotización
            3 => [1, 2, 4], // Vendido -> Cotización, Anulado, Facturado
            4 => [],     // Facturado -> No se puede cambiar
        ];

        return $transiciones[$estadoActual] ?? [];
    }


    public function index()
        {
            $cotizaciones = Cotizacion::with(['estado', 'cliente'])->get();
                return view('cotizaciones.index', compact('cotizaciones'));
        }

    public function create()
        {   
            $productos = Producto::all(); // Trae todos los productos
            return view('cotizaciones.create', compact('productos'));
        }

    public function show($id)
        {
            $cotizacion = Cotizacion::with(['lineas.producto', 'estado', 'cliente'])->findOrFail($id);
            return view('cotizaciones.show', compact('cotizacion'));
        }

    public function edit($id)
    {
        $cotizacion = Cotizacion::with(['lineas.producto', 'estado', 'cliente'])->findOrFail($id);
        $productos = Producto::all();
        $clientes = User::all();
        return view('cotizaciones.edit', compact('cotizacion', 'productos', 'clientes'));
    }

    public function update(Request $request, $id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        
        // Validar que no se pueda editar si está facturado
        if ($cotizacion->estado_id == 4) {
            return redirect()->back()->with('error', 'No se puede editar una cotización que ya está facturada electrónicamente.');
        }

        DB::transaction(function () use ($request, $cotizacion) {
            // Actualizar datos generales
            $cotizacion->update([
                'cliente_id' => $request->cliente_id,
            ]);

            // Eliminar líneas existentes
            $cotizacion->lineas()->delete();

            // Crear nuevas líneas
            $total = 0;
            foreach ($request->lineas as $linea) {
                $subtotal = $linea['cantidad'] * $linea['precio_unitario'];

                CotizacionLinea::create([
                    'cotizacion_id'   => $cotizacion->cotizacion_id,
                    'producto_id'     => $linea['producto_id'],
                    'cantidad'        => $linea['cantidad'],
                    'precio_unitario' => $linea['precio_unitario'],
                    'subtotal'        => $subtotal,
                ]);

                $total += $subtotal;
            }

            $cotizacion->update(['total' => $total]);

            // Registrar en auditoría
            Auditoria::create([
                'tabla'      => 'cotizaciones',
                'accion'     => 'UPDATE',
                'registro_id'=> $cotizacion->cotizacion_id,
                'usuario'    => auth()->user()->name ?? 'sistema',
                'detalle'    => 'Cotización actualizada con nuevos detalles',
            ]);
        });

        return redirect()->route('cotizaciones.show', $cotizacion->cotizacion_id)
                        ->with('success', 'Cotización actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        
        // Validar que no se pueda eliminar si está facturado
        if ($cotizacion->estado_id == 4) {
            return redirect()->back()->with('error', 'No se puede eliminar una cotización que ya está facturada electrónicamente.');
        }

        DB::transaction(function () use ($cotizacion) {
            // Registrar en auditoría antes de eliminar
            Auditoria::create([
                'tabla'      => 'cotizaciones',
                'accion'     => 'DELETE',
                'registro_id'=> $cotizacion->cotizacion_id,
                'usuario'    => auth()->user()->name ?? 'sistema',
                'detalle'    => 'Cotización eliminada',
            ]);

            // Eliminar cotización (las líneas se eliminan por cascade)
            $cotizacion->delete();
        });

        return redirect()->route('cotizaciones.index')
                        ->with('success', 'Cotización eliminada exitosamente.');
    }

}



