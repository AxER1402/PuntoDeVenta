<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Mostrar listado de productos (administración)
     */
    public function index()
    {
        $productos = Producto::paginate(10); // paginación de 10 productos
        return view('productos.index', compact('productos'));
    }

    /**
     * Mostrar formulario para crear un producto
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Guardar nuevo producto con imagen
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nombre', 'descripcion', 'precio', 'stock']);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($data);

        return redirect()->route('productos.index')
                         ->with('success', 'Producto creado correctamente ✅');
    }

    /**
     * Mostrar detalle de un producto
     */
    public function show(string $id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    /**
     * Mostrar formulario para editar un producto
     */
    public function edit(string $id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    /**
     * Actualizar producto con imagen
     */
    public function update(Request $request, string $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nombre', 'descripcion', 'precio', 'stock']);

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen && file_exists(storage_path('app/public/' . $producto->imagen))) {
                unlink(storage_path('app/public/' . $producto->imagen));
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        return redirect()->route('productos.index')
                         ->with('success', 'Producto actualizado correctamente ✨');
    }

    /**
     * Eliminar un producto
     */
    public function destroy(string $id)
    {
        $producto = Producto::findOrFail($id);

        // Eliminar imagen al borrar producto
        if ($producto->imagen && file_exists(storage_path('app/public/' . $producto->imagen))) {
            unlink(storage_path('app/public/' . $producto->imagen));
        }

        $producto->delete();

        return redirect()->route('productos.index')
                         ->with('success', 'Producto eliminado correctamente 🗑️');
    }

    /**
     * Vista de catálogo para clientes
     */
    public function catalogo()
    {
        $productos = Producto::paginate(12); // paginación de 12 productos
        return view('productos.catalogo', compact('productos'));
    }
}
