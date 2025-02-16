@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 col-lg-10 col-xl-8 mx-auto">
                <div class="card shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary fw-bold">Most Bought Products</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:60vh; width:100%">
                            <canvas id="mostBoughtProductsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('mostBoughtProductsChart').getContext('2d');
            var products = @json($products);
            var productNames = products.map(product => product.name);
            var productCounts = products.map(product => product.order_items_count);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: productNames,
                    datasets: [{
                        label: 'Number of Orders',
                        data: productCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
