@extends('layouts.app')

@section('title', 'Detalle de Venta')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Detalle de Venta #{{ $venta->id }}</h3>
            <small>Fecha: {{ $venta->created_at->format('d/m/Y H:i') }}</small>
        </div>
        <div class="card-body">
            {{-- Cliente y NIT/CF --}}
            <div class="mb-3">
                <h5>Cliente: <strong>{{ $venta->cliente->name ?? 'Sin asignar' }}</strong></h5>
                <h6>NIT / C.F.: <strong>{{ $venta->nit_cf ?? 'No proporcionado' }}</strong></h6>
            </div>

            {{-- Total --}}
            <h5>Total: <span class="badge bg-success">${{ number_format($venta->total, 2) }}</span></h5>

            {{-- Tabla de detalles --}}
            <div class="table-responsive mt-3">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-secondary">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto->nombre }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td>${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{ route('ventas.index') }}" class="btn btn-secondary mt-3">
                ← Volver a Ventas
            </a>
        </div>
    </div>
</div>
@endsection
