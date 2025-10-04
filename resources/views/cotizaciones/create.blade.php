@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">
        <i class="fas fa-file-invoice-dollar me-2 text-primary"></i> Nueva Cotización
    </h1>

    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user me-2"></i> Selección de Cliente</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('cotizaciones.store') }}" method="POST">
                @csrf

                {{-- Cliente --}}
                <div class="mb-4">
                    <label for="cliente_search" class="form-label fw-bold">Cliente</label>
                    <div class="input-group position-relative">
                        <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                        <input type="text" 
                               id="cliente_search" 
                               class="form-control" 
                               placeholder="Buscar cliente por nombre o correo..."
                               autocomplete="off">
                        <input type="hidden" name="cliente_id" id="cliente_id" required>
                        <div id="cliente_results" 
                             class="position-absolute w-100 bg-white border rounded shadow-sm mt-1"
                             style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;">
                        </div>
                    </div>
                    <div id="cliente_selected" class="mt-2" style="display: none;">
                        <div class="alert alert-info d-flex justify-content-between align-items-center mb-0">
                            <span id="cliente_selected_text"></span>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="clear_cliente">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Detalles --}}
                <h4 class="fw-bold mb-3"><i class="fas fa-boxes me-2"></i> Detalles de Productos</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Producto</th>
                                <th style="width: 150px;">Cantidad</th>
                                <th style="width: 200px;">Precio Unitario</th>
                                <th style="width: 60px;" class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="lineas">
                            <tr class="linea">
                                <td>
                                    <select name="lineas[0][producto_id]" class="form-select producto-select" required>
                                        <option value="">Seleccione producto</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->producto_id }}" data-precio="{{ $producto->precio }}">
                                                {{ $producto->nombre }} - ${{ number_format($producto->precio,2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="lineas[0][cantidad]" class="form-control" placeholder="0" min="1" required>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="lineas[0][precio_unitario]" class="form-control precio-input" placeholder="0.00" required>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger eliminar-linea">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-primary" id="agregar-linea">
                        <i class="fas fa-plus"></i> Agregar línea
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Cotización
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let lineaIndex = 1;
    let searchTimeout;

    // ====== Búsqueda de clientes ======
    const clienteSearch = document.getElementById('cliente_search');
    const clienteResults = document.getElementById('cliente_results');
    const clienteId = document.getElementById('cliente_id');
    const clienteSelected = document.getElementById('cliente_selected');
    const clienteSelectedText = document.getElementById('cliente_selected_text');
    const clearCliente = document.getElementById('clear_cliente');

    clienteSearch.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 2) {
            clienteResults.style.display = 'none';
            return;
        }
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchClientes(query);
        }, 300);
    });

    function searchClientes(query) {
        clienteResults.innerHTML = `
            <div class="p-3 text-center text-muted">
                <i class="fas fa-spinner fa-spin me-2"></i> Buscando clientes...
            </div>
        `;
        clienteResults.style.display = 'block';

        fetch(`{{ route('clientes.search') }}?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => displayResults(data))
            .catch(error => {
                console.error('Error:', error);
                clienteResults.innerHTML = `
                    <div class="p-3 text-center text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i> Error al buscar clientes
                    </div>
                `;
            });
    }

    function displayResults(clientes) {
        if (clientes.length === 0) {
            clienteResults.innerHTML = `
                <div class="p-3 text-center text-muted">
                    <i class="fas fa-search me-2"></i> No se encontraron clientes
                </div>
            `;
        } else {
            clienteResults.innerHTML = clientes.map(cliente => {
                const isExactMatch = !cliente.similarity_score;
                const matchType = isExactMatch ? 'text-success' : 'text-warning';
                const matchIcon = isExactMatch ? 'fas fa-check-circle' : 'fas fa-search';
                const matchText = isExactMatch ? 'Coincidencia exacta' : 'Usuario parecido';

                return `
                    <div class="p-2 border-bottom cliente-option" 
                         data-id="${cliente.id}" 
                         data-name="${cliente.name}" 
                         data-email="${cliente.email}"
                         style="cursor: pointer;">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-bold">${cliente.name}</div>
                                <small class="text-muted">${cliente.email}</small>
                            </div>
                            <div class="text-end ${matchType}">
                                <i class="${matchIcon} me-1"></i>
                                <small>${matchText}</small>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
        clienteResults.style.display = 'block';
    }

    // Selección de cliente
    clienteResults.addEventListener('click', function(e) {
        const option = e.target.closest('.cliente-option');
        if (option) {
            clienteId.value = option.dataset.id;
            clienteSearch.value = '';
            clienteResults.style.display = 'none';
            clienteSelectedText.textContent = `${option.dataset.name} (${option.dataset.email})`;
            clienteSelected.style.display = 'block';
        }
    });

    // Limpiar cliente seleccionado
    clearCliente.addEventListener('click', function() {
        clienteId.value = '';
        clienteSelected.style.display = 'none';
        clienteSearch.value = '';
    });

    // Ocultar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.position-relative')) {
            clienteResults.style.display = 'none';
        }
    });

    // ====== Manejo de líneas de productos ======
    const lineasTable = document.getElementById('lineas');

    document.getElementById('agregar-linea').addEventListener('click', function() {
        const nuevaFila = document.createElement('tr');
        nuevaFila.classList.add('linea');
        nuevaFila.innerHTML = `
            <td>
                <select name="lineas[${lineaIndex}][producto_id]" class="form-select producto-select" required>
                    <option value="">Seleccione producto</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->producto_id }}" data-precio="{{ $producto->precio }}">
                            {{ $producto->nombre }} - ${{ number_format($producto->precio,2) }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="lineas[${lineaIndex}][cantidad]" placeholder="Cantidad" class="form-control" required>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01" name="lineas[${lineaIndex}][precio_unitario]" placeholder="Precio" class="form-control precio-input" required>
                </div>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger eliminar-linea">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        lineasTable.appendChild(nuevaFila);
        lineaIndex++;
    });

    // Autocompletar precio según producto
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('producto-select')) {
            const precio = e.target.selectedOptions[0].getAttribute('data-precio');
            const precioInput = e.target.closest('tr').querySelector('.precio-input');
            if (precio && precioInput) {
                precioInput.value = precio;
            }
        }
    });

    // Eliminar línea de producto
    document.addEventListener('click', function(e) {
        if (e.target.closest('.eliminar-linea')) {
            const fila = e.target.closest('tr');
            if (fila && lineasTable.children.length > 1) {
                fila.remove();
            }
        }
    });
});
</script>
@endsection
