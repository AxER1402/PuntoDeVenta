@extends('layouts.app')

@section('title', 'Detalle de Cotización')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Cotización #{{ $cotizacion->cotizacion_id }}</h1>
        <div>
            <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Cotizaciones
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Información General -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>ID Cotización:</strong></td>
                            <td>{{ $cotizacion->cotizacion_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Cliente:</strong></td>
                            <td>
                                @if($cotizacion->cliente)
                                    {{ $cotizacion->cliente->name }}
                                    <br><small class="text-muted">{{ $cotizacion->cliente->email }}</small>
                                @else
                                    <span class="text-muted">Cliente no encontrado</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Fecha:</strong></td>
                            <td>{{ $cotizacion->fecha->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td>
                                <span class="badge bg-{{ $cotizacion->estado->nombre_estado == 'Activa' ? 'success' : 'secondary' }}">
                                    {{ $cotizacion->estado->nombre_estado }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Usuario:</strong></td>
                            <td>{{ $cotizacion->usuario_crea }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Resumen de Totales -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Resumen</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <strong>Total de Productos:</strong>
                        </div>
                        <div class="col-6 text-end">
                            {{ $cotizacion->lineas->count() }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <h5><strong>Total:</strong></h5>
                        </div>
                        <div class="col-6 text-end">
                            <h5 class="text-success">${{ number_format($cotizacion->total, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalles de Productos -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Detalles de Productos</h5>
        </div>
        <div class="card-body">
            @if($cotizacion->lineas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cotizacion->lineas as $linea)
                                <tr>
                                    <td>
                                        @if($linea->producto)
                                            {{ $linea->producto->nombre }}
                                        @else
                                            <span class="text-muted">Producto no encontrado</span>
                                        @endif
                                    </td>
                                    <td>{{ $linea->cantidad }}</td>
                                    <td>${{ number_format($linea->precio_unitario, 2) }}</td>
                                    <td>${{ number_format($linea->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th class="text-success">${{ number_format($cotizacion->total, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    No hay productos en esta cotización.
                </div>
            @endif
        </div>
    </div>

    <!-- Acciones -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Acciones</h6>
                    <small class="text-muted">Gestiona esta cotización</small>
                </div>
                <div class="btn-group">
                    @if($cotizacion->estado_id == 1)
                        <!-- Estado: Cotización -->
                        <form action="{{ route('cotizaciones.estado', [$cotizacion->cotizacion_id, 2]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning" 
                                    onclick="return confirm('¿Estás seguro de anular esta cotización?')">
                                <i class="fas fa-ban"></i> Anular
                            </button>
                        </form>
                        <form action="{{ route('cotizaciones.estado', [$cotizacion->cotizacion_id, 3]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" 
                                    onclick="return confirm('¿Marcar como vendido?')">
                                <i class="fas fa-check"></i> Marcar como Vendido
                            </button>
                        </form>
                    @elseif($cotizacion->estado_id == 2)
                        <!-- Estado: Anulado -->
                        <form action="{{ route('cotizaciones.estado', [$cotizacion->cotizacion_id, 1]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-undo"></i> Reactivar
                            </button>
                        </form>
                    @elseif($cotizacion->estado_id == 3)
                        <!-- Estado: Vendido -->
                        <form action="{{ route('cotizaciones.estado', [$cotizacion->cotizacion_id, 1]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-edit"></i> Volver a Cotización
                            </button>
                        </form>
                        <form action="{{ route('cotizaciones.estado', [$cotizacion->cotizacion_id, 2]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning" 
                                    onclick="return confirm('¿Estás seguro de anular esta venta?')">
                                <i class="fas fa-ban"></i> Anular
                            </button>
                        </form>
                        <form action="{{ route('cotizaciones.estado', [$cotizacion->cotizacion_id, 4]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" 
                                    onclick="return confirm('¿Facturar electrónicamente? Esta acción no se puede deshacer.')">
                                <i class="fas fa-file-invoice"></i> Facturar Electrónicamente
                            </button>
                        </form>
                    @elseif($cotizacion->estado_id == 4)
                        <!-- Estado: Facturado -->
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-lock"></i> Facturado - No se puede modificar
                        </span>
                    @endif
                    
                    <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> Ver Todas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection