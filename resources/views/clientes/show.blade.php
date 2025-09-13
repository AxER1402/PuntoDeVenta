@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Detalles del Cliente</h4>
                    <div class="btn-group">
                        <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Información Personal</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $cliente->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nombre:</strong></td>
                                    <td>{{ $cliente->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $cliente->email }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Información del Sistema</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Fecha de Registro:</strong></td>
                                    <td>{{ $cliente->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Última Actualización:</strong></td>
                                    <td>{{ $cliente->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email Verificado:</strong></td>
                                    <td>
                                        @if($cliente->email_verified_at)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Verificado
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-exclamation-triangle"></i> No verificado
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-muted mb-0">Acciones</h6>
                        <div class="btn-group">
                            <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar Cliente
                            </a>
                            <form action="{{ route('clientes.destroy', $cliente->id) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar este cliente? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Eliminar Cliente
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
