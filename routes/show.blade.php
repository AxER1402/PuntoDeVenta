@extends('layouts.app')

@section('title', 'Detalle de Venta #' . $venta->id)

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-dark text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Detalle de Venta #{{ $venta->id }}</h2>
                <span class="badge bg-light text-dark fs-6">
                    Fecha: {{ $venta->created_at->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <h4 class="mb-3">Productos</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 100px;">Imagen</th>
                            <th>Producto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio Unitario</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalles as $detalle)
                        <tr>
                            <td>
                                <img src="{{ $detalle->producto->imagen ? asset('storage/' . $detalle->producto->imagen) : 'https://via.placeholder.com/80' }}" 
                                     alt="{{ $detalle->producto->nombre }}" class="img-fluid rounded">
                            </td>
                            <td class="align-middle">{{ $detalle->producto->nombre }}</td>
                            <td class="text-center align-middle">{{ $detalle->cantidad }}</td>
                            <td class="text-end align-middle">${{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td class="text-end align-middle fw-bold">${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fs-5 fw-bold">Total General:</td>
                            <td class="text-end fs-5 fw-bold bg-light">${{ number_format($venta->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Historial
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Imprimir
            </button>
        </div>
    </div>
</div>
@endsection