@extends('layouts.app')

@section('title', 'Nuevo Producto')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Nuevo Producto</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Nombre --}}
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre *</label>
                    <input type="text" 
                           name="nombre" 
                           id="nombre" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           value="{{ old('nombre') }}" 
                           required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" 
                              id="descripcion" 
                              class="form-control @error('descripcion') is-invalid @enderror" 
                              rows="3">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Precio --}}
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio *</label>
                    <input type="number" 
                           step="0.01" 
                           name="precio" 
                           id="precio" 
                           class="form-control @error('precio') is-invalid @enderror" 
                           value="{{ old('precio') }}" 
                           required>
                    @error('precio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Stock --}}
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock *</label>
                    <input type="number" 
                           name="stock" 
                           id="stock" 
                           class="form-control @error('stock') is-invalid @enderror" 
                           value="{{ old('stock') }}" 
                           required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Imagen --}}
                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen del Producto</label>
                    <input type="file" 
                           name="imagen" 
                           id="imagen" 
                           class="form-control @error('imagen') is-invalid @enderror" 
                           accept="image/*">
                    <small class="text-muted">Formatos permitidos: jpg, jpeg, png. Máx 2MB.</small>
                    @error('imagen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Producto
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
