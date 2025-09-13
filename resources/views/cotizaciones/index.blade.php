@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Listado de Cotizaciones</h1>
    <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary">Nueva Cotización</a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cotizaciones as $c)
            <tr>
                <td>{{ $c->cotizacion_id }}</td>
                <td>
                    @if($c->cliente)
                        {{ $c->cliente->name }}
                        <br><small class="text-muted">{{ $c->cliente->email }}</small>
                    @else
                        <span class="text-muted">Cliente no encontrado</span>
                    @endif
                </td>
                <td>${{ number_format($c->total, 2) }}</td>
                <td>
                    <span class="badge bg-{{ $c->estado->nombre_estado == 'Activa' ? 'success' : 'secondary' }}">
                        {{ $c->estado->nombre_estado }}
                    </span>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('cotizaciones.show', $c->cotizacion_id) }}" class="btn btn-info btn-sm" title="Ver">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($c->estado_id != 4)
                            <a href="{{ route('cotizaciones.edit', $c->cotizacion_id) }}" class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif
                        @if($c->estado_id != 4)
                            <form action="{{ route('cotizaciones.destroy', $c->cotizacion_id) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta cotización?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>



@endsection
