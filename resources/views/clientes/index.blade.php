@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-primary fw-bold">Gestión de Clientes</h1>
        <a href="{{ route('clientes.create') }}" class="btn btn-success btn-lg shadow-sm">
            <i class="fas fa-plus me-1"></i> Nuevo Cliente
        </a>
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if($clientes->count() > 0)
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle text-center mb-0">
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
                        <tr class="align-middle">
                            <td class="fw-bold">{{ $cliente->id }}</td>
                            <td class="text-start ps-4">{{ $cliente->name }}</td>
                            <td>{{ $cliente->email }}</td>
                            <td>{{ $cliente->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('clientes.show', $cliente->id) }}" 
                                    class="btn btn-info btn-sm shadow-sm me-1" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" 
                                    class="btn btn-warning btn-sm shadow-sm me-1" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('clientes.destroy', $cliente->id) }}" 
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('¿Estás seguro de que quieres eliminar este cliente?')">
                                        @csrf
                                        @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm shadow-sm" title="Eliminar">
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
        {{-- No hay clientes --}}
        <div class="alert alert-info text-center shadow-sm rounded py-5">
            <i class="fas fa-user-friends fa-3x mb-3 text-primary"></i>
            <h4 class="fw-bold">No hay clientes registrados</h4>
            <p class="mb-3">Comienza agregando tu primer cliente para gestionar ventas y contactos.</p>
            <a href="{{ route('clientes.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-1"></i> Crear Primer Cliente
            </a>
        </div>
    @endif
</div>
@endsection
