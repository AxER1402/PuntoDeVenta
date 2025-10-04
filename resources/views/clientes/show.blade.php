@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-lg rounded-3 border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i> Detalles del Cliente
                    </h4>
                    <div class="btn-group">
                        
                        <a href="{{ route('clientes.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        {{-- Información Personal --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-id-card me-2"></i> Información Personal
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="fw-bold">ID:</td>
                                    <td>{{ $cliente->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nombre:</td>
                                    <td>{{ $cliente->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Email:</td>
                                    <td>{{ $cliente->email }}</td>
                                </tr>
                            </table>
                        </div>

                        {{-- Información del Sistema --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-cogs me-2"></i> Información del Sistema
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="fw-bold">Fecha de Registro:</td>
                                    <td>{{ $cliente->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Última Actualización:</td>
                                    <td>{{ $cliente->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Email Verificado:</td>
                                    <td>
                                        @if($cliente->email_verified_at)
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="fas fa-check-circle"></i> Verificado
                                            </span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2">
                                                <i class="fas fa-times-circle"></i> No verificado
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    {{-- Acciones --}}
                        <div class="alert alert-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-muted"><i class="fas fa-tools me-2"></i> Acciones</h6>
                            <div class="btn-group">
                                <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning me-2">
                                    <i class="fas fa-edit"></i> Editar Cliente
                                </a>
                                <form action="{{ route('clientes.destroy', $cliente->id) }}" 
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('¿Estás seguro de que quieres eliminar este cliente? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i> Eliminar Cliente
                                    </button>
                                </form>
                            </div>
                        </div>
                
                </div>
            </div>
        </div>
    </div>
</div>  
@endsection
