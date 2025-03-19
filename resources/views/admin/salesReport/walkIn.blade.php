@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Earnings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($earnings, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Finished Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $finishedOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card" style="border-color: #D3A780;">
                <div class="card-header" style="background-color: #B68D67; color: white;">
                    <h5 class="mb-0">Orders</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('sales-report.walk-in') }}" class="form-inline mb-3">
                        <select name="filter" class="form-control mr-2" onchange="toggleCustomDateFields(this.value)">
                            <option value="">Select Filter</option>
                            <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="custom" {{ request('filter') == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                        <input type="date" name="start_date" id="start_date" class="form-control mr-2" style="display: none;" value="{{ request('start_date') }}">
                        <input type="date" name="end_date" id="end_date" class="form-control mr-2" style="display: none;" value="{{ request('end_date') }}">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>

                    <table id="orders-table" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->total_amount }}</td>
                                <td>{{ $order->status }}</td>
                                <td>
                                    <a href="{{ url('admin/orders/' . $order->id . '/download') }}" class="btn btn-success btn-sm">Print</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#orders-table').DataTable({
                "order": [[0, "desc"]] // Order by the first column (Order ID) in descending order
            });
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

        document.addEventListener('DOMContentLoaded', function() {
            toggleCustomDateFields('{{ request('filter') }}');
        });
    </script>
@endpush
