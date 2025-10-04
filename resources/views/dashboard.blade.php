@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-center">Panel de Control</h1>

    <div class="row g-4">

        {{-- Tarjetas resumen --}}
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Ventas</h5>
                    <h3 class="card-text">{{ $totalVentas }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Clientes</h5>
                    <h3 class="card-text">{{ $totalClientes }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Productos</h5>
                    <h3 class="card-text">{{ $totalProductos }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Ventas Hoy</h5>
                    <h3 class="card-text">{{ $ventasHoy }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Gráficos --}}
    <div class="row mt-5 g-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    Ventas Mensuales
                </div>
                <div class="card-body">
                    <canvas id="ventasChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    Clientes Nuevos
                </div>
                <div class="card-body">
                    <canvas id="clientesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ventasCtx = document.getElementById('ventasChart').getContext('2d');
const ventasChart = new Chart(ventasCtx, {
    type: 'line',
    data: {
        labels: @json($meses), // ["Enero", "Febrero", ...]
        datasets: [{
            label: 'Ventas',
            data: @json($ventasMensuales),
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true }
        }
    }
});

const clientesCtx = document.getElementById('clientesChart').getContext('2d');
const clientesChart = new Chart(clientesCtx, {
    type: 'bar',
    data: {
        labels: @json($meses),
        datasets: [{
            label: 'Clientes Nuevos',
            data: @json($clientesMensuales),
            backgroundColor: 'rgba(255, 159, 64, 0.7)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection
