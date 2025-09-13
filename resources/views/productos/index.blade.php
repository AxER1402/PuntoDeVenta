<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="container">
    <h1 class="mb-4">Productos</h1>

    <!-- Botón Nuevo Producto -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
        Nuevo Producto
    </button>

    <!-- Tabla de productos -->
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            <tr>
                <td>{{ $producto->producto_id }}</td>
                <td>{{ $producto->nombre }}</td>
                <td>${{ number_format($producto->precio, 2) }}</td>
                <td>{{ $producto->stock }}</td>
                <td>
                    <!-- Botón Editar -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $producto->producto_id }}">
                        Editar
                    </button>

                    <!-- Form Eliminar -->
                    <form action="{{ route('productos.destroy', $producto->producto_id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este producto?')">Eliminar</button>
                    </form>
                </td>
            </tr>

            <!-- Modal Editar Producto -->
            <div class="modal fade" id="editModal{{ $producto->producto_id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $producto->producto_id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('productos.update', $producto->producto_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{{ $producto->producto_id }}">Editar Producto</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" class="form-control" value="{{ $producto->nombre }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Descripción</label>
                                    <textarea name="descripcion" class="form-control">{{ $producto->descripcion }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Precio</label>
                                    <input type="number" step="0.01" name="precio" class="form-control" value="{{ $producto->precio }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Stock</label>
                                    <input type="number" name="stock" class="form-control" value="{{ $producto->stock }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success">Guardar cambios</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Crear Producto -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('productos.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Precio</label>
                        <input type="number" step="0.01" name="precio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Stock</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Producto</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
