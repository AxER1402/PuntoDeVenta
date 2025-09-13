<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Mostrar listado de productos
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    // Mostrar formulario para crear un producto
    public function create()
    {
        return view('productos.create');
    }

    // Guardar nuevo producto
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer'
        ]);

        Producto::create($request->all());

        return redirect()->route('productos.index')
                         ->with('success', 'Producto creado correctamente');
    }

    // Mostrar detalle de un producto
    public function show(string $id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    // Mostrar formulario para editar un producto
    public function edit(string $id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    // Actualizar un producto
    public function update(Request $request, string $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer'
        ]);

        $producto->update($request->all());

        return redirect()->route('productos.index')
                         ->with('success', 'Producto actualizado correctamente');
    }

    // Eliminar un producto
    public function destroy(string $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')
                         ->with('success', 'Producto eliminado correctamente');
    }
}
