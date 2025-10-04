@extends('layouts.app')

@section('title', 'Historial de Ventas')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h1 class="mb-0">Historial de Ventas</h1>
        </div>
        <div class="card-body">
            @if($ventas->isEmpty())
                <div class="alert alert-info text-center">
                    No se ha realizado ninguna venta todavía.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Venta</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ventas as $venta)
                            <tr>
                                <td>#{{ $venta->id }}</td>
                                <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                                <td>${{ number_format($venta->total, 2) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('ventas.show', $venta) }}" class="btn btn-info btn-sm">Ver Detalle</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">{{ $ventas->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection