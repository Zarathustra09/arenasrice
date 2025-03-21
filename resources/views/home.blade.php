@extends('layouts.app')

@section('content')

    <div class="row">
        <!-- Earnings (Monthly) Card Example -->

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Earnings (Today)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($todaysSales, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Earnings (Weekly)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($weeklySales, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Earnings (Monthly)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($monthlyEarnings, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Annual) Card Example -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Earnings (Annual)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($annualEarnings, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders Card Example -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Products Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" onclick="scrollToTable('lowStockProductsTable')">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Low Stock Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockProductsCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Ingredients Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" onclick="scrollToTable('lowStockIngredientsTable')">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Low Stock Ingredients</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockIngredientsCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-mortar-pestle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Materials Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" onclick="scrollToTable('lowStockContainersTable')">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Low Stock Materials</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockContainersCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-bag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid px-4">
{{--        <div class="row mb-4">--}}
{{--            <form method="GET" action="{{ route('home') }}" class="form-inline">--}}
{{--                <select name="filter" class="form-control mr-2" onchange="toggleCustomDateFields(this.value)">--}}
{{--                    <option value="">Select Filter</option>--}}
{{--                    <option value="today">Today</option>--}}
{{--                    <option value="week">This Week</option>--}}
{{--                    <option value="month">This Month</option>--}}
{{--                    <option value="custom">Custom</option>--}}
{{--                </select>--}}
{{--                <input type="date" name="start_date" id="start_date" class="form-control mr-2" style="display: none;">--}}
{{--                <input type="date" name="end_date" id="end_date" class="form-control mr-2" style="display: none;">--}}
{{--                <button type="submit" class="btn btn-primary">Filter</button>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--        <div class="row">--}}
{{--            <div class="card shadow mb-4">--}}
{{--                <div class="card-header py-3">--}}
{{--                    <h6 class="m-0 font-weight-bold text-primary">Bar Chart</h6>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <div class="chart-bar">--}}
{{--                        <canvas id="barchart"></canvas>--}}
{{--                    </div>--}}
{{--                    <hr>--}}
{{--                    <div class="text-center">--}}
{{--                        <h6 class="m-0 font-weight-bold text-primary">Total Sales: ₱{{ number_format($totalSalesSum, 2) }}</h6>--}}
{{--                        <h6 class="m-0 font-weight-bold text-primary">Today's Sale: ₱{{ number_format($todaysSales, 2) }}</h6>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>



    <!-- Low Stock Products Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Low Stock Products</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="lowStockProductsTable" width="100%" cellspacing="0">
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
                                @elseif($product->stock < $product->low_stock_threshold)
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

    <!-- Low Stock Ingredients Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Low Stock Ingredients</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="lowStockIngredientsTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lowStockIngredients as $ingredient)
                        <tr>
                            <td>{{ $ingredient->id }}</td>
                            <td>{{ $ingredient->name }}</td>
                            <td>{{ $ingredient->stock }}</td>
                            <td>
                                @if($ingredient->stock == 0)
                                    <span class="badge bg-danger">No Stock</span>
                                @elseif($ingredient->stock < $ingredient->low_stock_threshold)
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

    <!-- Low Stock Materials Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Low Stock Materials</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="lowStockContainersTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lowStockContainers as $container)
                        <tr>
                            <td>{{ $container->id }}</td>
                            <td>{{ $container->name }}</td>
                            <td>{{ $container->quantity }}</td>
                            <td>
                                @if($container->quantity == 0)
                                    <span class="badge bg-danger">No Stock</span>
                                @elseif($container->quantity < $container->low_stock_threshold)
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
            $('#lowStockProductsTable').DataTable();
            $('#lowStockIngredientsTable').DataTable();
            $('#lowStockContainersTable').DataTable();
        });

        function scrollToTable(tableId) {
            document.getElementById(tableId).scrollIntoView({ behavior: 'smooth' });
        }

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
                    hoverBackgroundColor: "#000000",
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
                            color: '#ffffff'
                        },
                        backgroundColor: "#000000",
                        bodyFont: {
                            color: "#ffffff"
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
