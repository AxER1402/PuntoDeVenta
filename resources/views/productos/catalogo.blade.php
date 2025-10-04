@extends('layouts.app')

@section('title', 'Catálogo de Productos')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Catálogo de Productos</h1>

    <div class="row">
        @foreach($productos as $producto)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    {{-- Imagen del producto --}}
                    <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : 'https://via.placeholder.com/200' }}" 
                         class="card-img-top" alt="{{ $producto->nombre }}" style="height:200px; object-fit:cover;">
                    
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $producto->nombre }}</h5>
                        <p class="card-text" style="height:50px; overflow:hidden; text-overflow:ellipsis;">
                            {{ $producto->descripcion }}
                        </p>
                        <h6 class="text-primary fw-bold">${{ number_format($producto->precio, 2) }}</h6>

                        {{-- Cantidad disponible --}}
                        <p class="mb-0">
                            <small class="text-muted">Disponibles: {{ $producto->stock ?? 0 }}</small>
                        </p>
                    </div>

                    <div class="card-footer text-center">
                        <form class="add-to-cart-form" data-id="{{ $producto->producto_id }}" data-nombre="{{ $producto->nombre }}">
                            <input type="number" name="cantidad" value="1" min="1" max="{{ $producto->stock ?? 0 }}" class="form-control mb-2">
                            <button type="submit" class="btn btn-success w-100" {{ ($producto->stock ?? 0) == 0 ? 'disabled' : '' }}>
                                Agregar al carrito 🛒
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Paginación --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $productos->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e){
            e.preventDefault();

            const id = this.dataset.id;
            const nombre = this.dataset.nombre;
            const cantidad = this.querySelector('input[name="cantidad"]').value;

            fetch(`/carrito/agregar/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                body: JSON.stringify({ cantidad: parseInt(cantidad) })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    const cartCount = document.getElementById('cart-count');
                    if(cartCount) cartCount.textContent = data.carrito_count;

                    alert(`Se agregaron ${cantidad} unidad(es) de "${nombre}" al carrito.`);
                }
            })
            .catch(err => console.error(err));
        });
    });
});
</script>
@endsection
