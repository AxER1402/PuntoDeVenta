@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Listado de Cotizaciones</h4>
            <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nueva Cotización
            </a>
        </div>
        <div class="card-body">
            {{-- Mensajes de éxito o error --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Tabla --}}
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cotizaciones as $c)
                        <tr>
                            <td>{{ $c->cotizacion_id }}</td>
                            <td class="text-start">
                                @if($c->cliente)
                                    <strong>{{ $c->cliente->name }}</strong><br>
                                    <small class="text-muted"><i class="fas fa-envelope"></i> {{ $c->cliente->email }}</small>
                                @else
                                    <span class="text-muted">Cliente no encontrado</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-success">${{ number_format($c->total, 2) }}</span>
                            </td>
                            <td>
                                <span class="badge rounded-pill 
                                    {{ $c->estado->nombre_estado == 'Activa' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $c->estado->nombre_estado }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('cotizaciones.show', $c->cotizacion_id) }}" 
                                       class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($c->estado_id != 4)
                                        <a href="{{ route('cotizaciones.edit', $c->cotizacion_id) }}" 
                                           class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('cotizaciones.destroy', $c->cotizacion_id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta cotización?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle"></i> No hay cotizaciones registradas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Script para tooltips --}}
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
@endsection
