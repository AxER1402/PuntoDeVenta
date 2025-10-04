<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Cotizaciones')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 230px;
            background-color: #084786;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            font-size: 15px;
        }
        .sidebar a:hover {
            background-color: #3b87d3;
        }
        .content {
            margin-left: 230px; /* espacio para la sidebar */
            padding: 20px;
        }
        .cart-btn {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
        }

        .title{
            padding: 5px !important;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center mb-4  title">Punto de Venta UMG</h4>
        
        <a href="{{ route('dashboard') }}"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="{{ route('cotizaciones.index') }}"><i class="fas fa-file-invoice"></i> Cotizaciones</a>
        <a href="{{ route('ventas.index') }}"><i class="fas fa-shopping-bag"></i> Ventas</a>
        <a href="{{ route('cotizaciones.create') }}"><i class="fas fa-plus-circle"></i> Nueva Cotización</a>
        <a href="{{ route('productos.index') }}"><i class="fas fa-box"></i> Productos</a>
        <a href="{{ route('clientes.index') }}"><i class="fas fa-users"></i> Clientes</a>
        <a href="{{ route('productos.catalogo') }}"><i class="fas fa-th"></i> Catálogo</a>
        

        <!-- Carrito -->
        <div class="cart-btn">
            <a href="{{ route('carrito.index') }}" class="btn btn-outline-light position-relative">
                <i class="fas fa-shopping-cart"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ session('carrito') ? count(session('carrito')) : 0 }}
                </span>
            </a>
        </div>
    </div>

    <!-- Contenido -->
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>
