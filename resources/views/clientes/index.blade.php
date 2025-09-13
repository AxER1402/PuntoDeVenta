@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Clientes</h1>
        <a href="{{ route('clientes.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo Cliente
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($clientes->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->id }}</td>
                            <td>{{ $cliente->name }}</td>
                            <td>{{ $cliente->email }}</td>
                            <td>{{ $cliente->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('clientes.show', $cliente->id) }}" 
                                       class="btn btn-info btn-sm" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" 
                                       class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('clientes.destroy', $cliente->id) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de que quieres eliminar este cliente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info text-center">
            <h4>No hay clientes registrados</h4>
            <p>Comienza agregando tu primer cliente.</p>
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Crear Primer Cliente
            </a>
        </div>
    @endif
</div>
@endsection
