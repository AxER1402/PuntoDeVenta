@extends('layouts.app')

@section('content')
<style>
    .cliente-option.exact {
        border-left: 3px solid #28a745;
    }
    .cliente-option.similar {
        border-left: 3px solid #ffc107;
    }
    .cliente-option:hover {
        background-color: #f8f9fa !important;
    }
    #cliente_results {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container">
    <h1>Nueva Cotización</h1>

    <form action="{{ route('cotizaciones.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="cliente_search" class="form-label">Cliente</label>
            <div class="position-relative">
                <input type="text" 
                       id="cliente_search" 
                       class="form-control" 
                       placeholder="Buscar cliente por nombre o email..."
                       autocomplete="off">
                <input type="hidden" name="cliente_id" id="cliente_id" required>
                <div id="cliente_results" class="position-absolute w-100 bg-white border rounded shadow-lg" 
                     style="top: 100%; left: 0; z-index: 1000; display: none; max-height: 200px; overflow-y: auto;">
                </div>
            </div>
            <div id="cliente_selected" class="mt-2" style="display: none;">
                <div class="alert alert-info d-flex justify-content-between align-items-center">
                    <span id="cliente_selected_text"></span>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="clear_cliente">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <h3>Detalles</h3>
        <div id="lineas">
            <div class="linea row mb-2">
                <div class="col">
                    <select name="lineas[0][producto_id]" class="form-control producto-select" required>
                        <option value="">Seleccione producto</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->producto_id }}" data-precio="{{ $producto->precio }}">
                                {{ $producto->nombre }} - ${{ number_format($producto->precio,2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <input type="number" name="lineas[0][cantidad]" placeholder="Cantidad" class="form-control" required>
                </div>
                <div class="col">
                    <input type="number" step="0.01" name="lineas[0][precio_unitario]" placeholder="Precio" class="form-control precio-input" required>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" id="agregar-linea">Agregar línea</button>
        <button type="submit" class="btn btn-success">Guardar Cotización</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let lineaIndex = 1;
    let searchTimeout;

    // Funcionalidad de búsqueda de clientes
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
        // Mostrar indicador de carga
        clienteResults.innerHTML = `
            <div class="p-3 text-center text-muted">
                <i class="fas fa-spinner fa-spin me-2"></i>
                <div>Buscando clientes...</div>
            </div>
        `;
        clienteResults.style.display = 'block';

        fetch(`{{ route('clientes.search') }}?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                displayResults(data);
            })
            .catch(error => {
                console.error('Error:', error);
                clienteResults.innerHTML = `
                    <div class="p-3 text-center text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>Error al buscar clientes</div>
                        <small>Intenta de nuevo</small>
                    </div>
                `;
            });
    }

    function displayResults(clientes) {
        if (clientes.length === 0) {
            clienteResults.innerHTML = `
                <div class="p-3 text-center text-muted">
                    <i class="fas fa-search me-2"></i>
                    <div>No se encontraron clientes</div>
                    <small>Intenta con un nombre o email diferente</small>
                </div>
            `;
        } else {
            clienteResults.innerHTML = clientes.map((cliente, index) => {
                const isExactMatch = !cliente.similarity_score;
                const matchType = isExactMatch ? 'exact' : 'similar';
                const matchIcon = isExactMatch ? 'fas fa-check-circle text-success' : 'fas fa-search text-warning';
                const matchText = isExactMatch ? 'Coincidencia exacta' : 'Usuario parecido';
                
                return `
                    <div class="p-2 border-bottom cliente-option ${matchType}" 
                         data-id="${cliente.id}" 
                         data-name="${cliente.name}" 
                         data-email="${cliente.email}" 
                         style="cursor: pointer; transition: background-color 0.2s;"
                         onmouseover="this.style.backgroundColor='#f8f9fa'"
                         onmouseout="this.style.backgroundColor=''">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="fw-bold">${cliente.name}</div>
                                <small class="text-muted">${cliente.email}</small>
                            </div>
                            <div class="text-end">
                                <i class="${matchIcon} me-1"></i>
                                <small class="text-muted">${matchText}</small>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
        clienteResults.style.display = 'block';
    }

    // Seleccionar cliente
    clienteResults.addEventListener('click', function(e) {
        const option = e.target.closest('.cliente-option');
        if (option) {
            const id = option.dataset.id;
            const name = option.dataset.name;
            const email = option.dataset.email;
            
            clienteId.value = id;
            clienteSearch.value = '';
            clienteResults.style.display = 'none';
            clienteSelectedText.textContent = `${name} (${email})`;
            clienteSelected.style.display = 'block';
        }
    });

    // Limpiar selección
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

    // Funcionalidad de líneas de productos
    document.getElementById('agregar-linea').addEventListener('click', function() {
        const lineasDiv = document.getElementById('lineas');
        const nuevaLinea = document.createElement('div');
        nuevaLinea.classList.add('linea', 'row', 'mb-2');
        nuevaLinea.innerHTML = `
            <div class="col">
                <select name="lineas[${lineaIndex}][producto_id]" class="form-control producto-select" required>
                    <option value="">Seleccione producto</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->producto_id }}" data-precio="{{ $producto->precio }}">
                            {{ $producto->nombre }} - ${{ number_format($producto->precio,2) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <input type="number" name="lineas[${lineaIndex}][cantidad]" placeholder="Cantidad" class="form-control" required>
            </div>
            <div class="col">
                <input type="number" step="0.01" name="lineas[${lineaIndex}][precio_unitario]" placeholder="Precio" class="form-control precio-input" required>
            </div>
        `;
        lineasDiv.appendChild(nuevaLinea);
        lineaIndex++;
    });

    // Autocompletar precio al seleccionar producto
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('producto-select')) {
            const precio = e.target.selectedOptions[0].getAttribute('data-precio');
            const precioInput = e.target.closest('.linea').querySelector('.precio-input');
            if (precio && precioInput) {
                precioInput.value = precio;
            }
        }
    });
});
</script>
@endsection