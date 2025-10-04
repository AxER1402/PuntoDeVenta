@extends('layouts.app')

@section('title', 'Ventas')

@section('content')
<div class="container py-4">
    <h1 class="text-center mb-4">Lista de Ventas</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Éxito!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($ventas->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID Venta</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventas as $venta)
                        <tr class="align-middle" style="transition: background 0.2s;">
                            <td>#{{ $venta->id }}</td>
                            <td>
                                @if($venta->cliente)
                                    {{ $venta->cliente->name }}
                                @else
                                    <span class="text-muted fst-italic">Consumidor Final</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success px-3 py-2">
                                    ${{ number_format($venta->total, 2) }}
                                </span>
                            </td>
                            <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('ventas.show', $venta->id) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    {{-- Aquí podrías agregar editar o imprimir si quieres --}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $ventas->links() }}
        </div>
    @else
        <div class="alert alert-warning text-center">
            No hay ventas registradas.
        </div>
    @endif
</div>

{{-- Pequeño hover en filas --}}
<style>
    table.table-hover tbody tr:hover {
        background-color: #f1f5f9;
    }
</style>
@endsection
