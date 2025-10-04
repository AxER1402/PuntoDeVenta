<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Venta;     
use App\Models\DetalleVenta; 

class CarritoController extends Controller
{
    // Mostrar carrito
    public function index(Request $request)
    {
        $carrito = $request->session()->get('carrito', []);
        return view('carrito.index', compact('carrito'));
    }

    // Agregar producto al carrito
    public function agregar(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $carrito = $request->session()->get('carrito', []);
        $cantidad = $request->input('cantidad', 1);

        // Validar stock
        if ($producto->stock < $cantidad) {
            return response()->json([
                'success' => false,
                'message' => "No hay suficiente stock de {$producto->nombre}."
            ]);
        }

        if(isset($carrito[$id])){
            $nuevoCantidad = $carrito[$id]['cantidad'] + $cantidad;
            if($nuevoCantidad > $producto->stock){
                return response()->json([
                    'success' => false,
                    'message' => "No puedes agregar más de {$producto->stock} unidades de {$producto->nombre}."
                ]);
            }
            $carrito[$id]['cantidad'] = $nuevoCantidad;
        } else {
            $carrito[$id] = [
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $cantidad,
                'imagen' => $producto->imagen,
            ];
        }

        $request->session()->put('carrito', $carrito);

        return response()->json([
            'success' => true,
            'carrito_count' => count($carrito)
        ]);
    }

    // Eliminar producto del carrito
    public function eliminar(Request $request, $id)
    {
        $carrito = $request->session()->get('carrito', []);
        if(isset($carrito[$id])){
            unset($carrito[$id]);
        }
        $request->session()->put('carrito', $carrito);
        return redirect()->back()->with('success', 'Producto eliminado del carrito');
    }

    // Generar venta desde carrito
    public function generarVenta(Request $request)
    {
        $carrito = $request->session()->get('carrito', []);
        if(empty($carrito)){
            return redirect()->back()->with('error', 'El carrito está vacío');
        }

        $total = 0;

        // Validar stock antes de crear la venta
        foreach($carrito as $id => $item){
            $producto = Producto::findOrFail($id);
            if($producto->stock < $item['cantidad']){
                return redirect()->back()->with('error', "No hay suficiente stock para {$producto->nombre}");
            }
            $total += $item['precio'] * $item['cantidad'];
        }

        // Crear venta con cliente y NIT/CF opcionales
        $venta = Venta::create([
    'total'      => $total,
    'estado_id'  => 3, // Vendido pero no facturado electrónicamente
    'cliente_id' => $request->input('cliente_id') ?? null, // Cliente seleccionado en el carrito
    'nit_cf'     => $request->input('nit_cf') ?? null,    // Opcional: NIT / C.F.
]);

        // Guardar detalles de venta y descontar stock
        foreach($carrito as $id => $item){
            $producto = Producto::findOrFail($id);

            $venta->detalles()->create([
                'producto_id' => $id,
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio'],
            ]);

            // Descontar del stock
            $producto->stock -= $item['cantidad'];
            $producto->save();
        }

        // Vaciar carrito
        $request->session()->forget('carrito');

        return redirect()->route('productos.catalogo')->with('success', 'Venta realizada correctamente');
    }
}
