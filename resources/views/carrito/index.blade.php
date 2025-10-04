@extends('layouts.app')

@section('title', 'Carrito de Compras')

@section('content')
<div class="container py-4">
    <h1 class="text-center mb-4">Carrito de Compras</h1>

    @if(empty($carrito))
        <div class="alert alert-info text-center">El carrito está vacío</div>
    @else
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Selección de cliente --}}
                <div class="mb-3">
                    <label for="cliente_search" class="form-label fw-bold">Cliente</label>
                    <div class="input-group position-relative">
                        <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                        <input type="text" 
                               id="cliente_search" 
                               class="form-control" 
                               placeholder="Buscar cliente por nombre o correo..."
                               autocomplete="off">
                        <input type="hidden" name="cliente_id" id="cliente_id">
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

                {{-- NIT / Consumidor Final --}}
                <div class="mb-4 form-check">
                    <input class="form-check-input" type="checkbox" value="" id="consumidor_final">
                    <label class="form-check-label fw-bold" for="consumidor_final">
                        Consumidor Final
                    </label>
                </div>

                <div class="mb-3">
                    <label for="nit_cf" class="form-label fw-bold">NIT / C.F.</label>
                    <input type="text" name="nit_cf" id="nit_cf" class="form-control" placeholder="Ingrese NIT o C.F.">
                </div>

                {{-- Tabla carrito --}}
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Producto</th>
                                <th>Imagen</th>
                                <th>Precio Unitario</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($carrito as $id => $item)
                                @php $subtotal = $item['precio'] * $item['cantidad']; $total += $subtotal; @endphp
                                <tr>
                                    <td>{{ $item['nombre'] }}</td>
                                    <td>
                                        <img src="{{ $item['imagen'] ? asset('storage/' . $item['imagen']) : 'https://via.placeholder.com/80' }}" width="80" height="80">
                                    </td>
                                    <td>${{ number_format($item['precio'],2) }}</td>
                                    <td>{{ $item['cantidad'] }}</td>
                                    <td>${{ number_format($subtotal,2) }}</td>
                                    <td>
                                        <form action="{{ url('/carrito/eliminar/'.$id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="table-secondary">
                                <td colspan="4" class="text-end fw-bold">Total</td>
                                <td colspan="2" class="fw-bold">${{ number_format($total,2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Botón generar venta --}}
                <form action="{{ route('carrito.generar') }}" method="POST" class="mt-3" id="form_venta">
                    @csrf
                    <input type="hidden" name="cliente_id" id="venta_cliente_id">
                    <input type="hidden" name="nit_cf" id="venta_nit_cf">
                    <button class="btn btn-success w-100" id="btn_generar_venta" disabled>
                        <i class="fas fa-cart-plus me-2"></i> Generar Venta
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clienteSearch = document.getElementById('cliente_search');
    const clienteResults = document.getElementById('cliente_results');
    const clienteId = document.getElementById('cliente_id');
    const clienteSelected = document.getElementById('cliente_selected');
    const clienteSelectedText = document.getElementById('cliente_selected_text');
    const clearCliente = document.getElementById('clear_cliente');
    const btnGenerar = document.getElementById('btn_generar_venta');
    const ventaClienteId = document.getElementById('venta_cliente_id');
    const nitInput = document.getElementById('nit_cf');
    const ventaNitInput = document.getElementById('venta_nit_cf');
    const consumidorFinal = document.getElementById('consumidor_final');

    let searchTimeout;

    // Búsqueda cliente
    clienteSearch.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 2) {
            clienteResults.style.display = 'none';
            return;
        }
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetch(`{{ route('clientes.search') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if(data.length === 0) {
                        clienteResults.innerHTML = `<div class="p-3 text-center text-muted">No se encontraron clientes</div>`;
                    } else {
                        clienteResults.innerHTML = data.map(c => `
                            <div class="p-2 border-bottom cliente-option" 
                                 data-id="${c.id}" data-name="${c.name}" data-email="${c.email}" style="cursor:pointer">
                                <div class="fw-bold">${c.name}</div>
                                <small class="text-muted">${c.email}</small>
                            </div>
                        `).join('');
                    }
                    clienteResults.style.display = 'block';
                });
        }, 300);
    });

    // Selección cliente
    clienteResults.addEventListener('click', function(e) {
        const option = e.target.closest('.cliente-option');
        if(option){
            clienteId.value = option.dataset.id;
            ventaClienteId.value = option.dataset.id;
            clienteSearch.value = '';
            clienteResults.style.display = 'none';
            clienteSelectedText.textContent = `${option.dataset.name} (${option.dataset.email})`;
            clienteSelected.style.display = 'block';
            btnGenerar.disabled = false;
            consumidorFinal.checked = false;
        }
    });

    // Limpiar cliente
    clearCliente.addEventListener('click', function() {
        clienteId.value = '';
        ventaClienteId.value = '';
        clienteSelected.style.display = 'none';
        btnGenerar.disabled = true;
    });

    // Consumidor Final
    consumidorFinal.addEventListener('change', function() {
    if(this.checked){
        // Solo llenar NIT como C.F.
        ventaNitInput.value = 'C.F.';
        btnGenerar.disabled = false;
    } else {
        ventaNitInput.value = nitInput.value;
        btnGenerar.disabled = !clienteId.value && !ventaNitInput.value;
    }
});

    

    // Sincronizar NIT
    nitInput.addEventListener('input', function() {
        ventaNitInput.value = this.value;
        btnGenerar.disabled = !clienteId.value && !ventaNitInput.value;
    });

    // Ocultar resultados al hacer clic fuera
    document.addEventListener('click', function(e){
        if(!e.target.closest('.position-relative')){
            clienteResults.style.display = 'none';
        }
    });
});
</script>
@endsection
