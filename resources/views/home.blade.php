@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <form method="GET" action="{{ route('home') }}" class="form-inline">
                <select name="filter" class="form-control mr-2" onchange="toggleCustomDateFields(this.value)">
                    <option value="">Select Filter</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="custom">Custom</option>
                </select>
                <input type="date" name="start_date" id="start_date" class="form-control mr-2" style="display: none;">
                <input type="date" name="end_date" id="end_date" class="form-control mr-2" style="display: none;">
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
        <div class="row">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bar Chart</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="barchart"></canvas>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Low Stock Products</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="lowStockTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lowStockProducts as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                @if($product->stock == 0)
                                    <span class="badge bg-danger">No Stock</span>
                                @elseif($product->stock < 20)
                                    <span class="badge bg-warning text-dark">Low Stock</span>
                                @else
                                    <span class="badge bg-success">In Stock</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#lowStockTable').DataTable();
        });

        function toggleCustomDateFields(value) {
            if (value === 'custom') {
                document.getElementById('start_date').style.display = 'inline-block';
                document.getElementById('end_date').style.display = 'inline-block';
            } else {
                document.getElementById('start_date').style.display = 'none';
                document.getElementById('end_date').style.display = 'none';
            }
        }

        var productNames = @json($productNames);
        var totalSales = @json($totalSales);

        var ctx = document.getElementById("barchart").getContext('2d');
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: productNames,
                datasets: [{
                    label: "Total Sales (₱)",
                    backgroundColor: "#4e73df",
                    hoverBackgroundColor: "#000000", // Change to black on hover
                    borderColor: "#4e73df",
                    data: totalSales,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 10
                        },
                        maxBarThickness: 25,
                    },
                    y: {
                        min: 0,
                        max: Math.max(...totalSales) + 5,
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                        },
                        grid: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    },
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        titleMarginBottom: 10,
                        titleFont: {
                            size: 14,
                            color: '#ffffff' // Make title text white
                        },
                        backgroundColor: "#000000", // Change tooltip background to black
                        bodyFont: {
                            color: "#ffffff" // Change text color to white for visibility
                        },
                        borderColor: '#ffffff',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                        callbacks: {
                            label: function (tooltipItem) {
                                return tooltipItem.dataset.label + ': ₱' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
