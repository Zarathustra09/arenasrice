<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function indexWalkin(Request $request)
    {
        $query = Order::whereNull('user_id');

        if ($request->has('filter')) {
            switch ($request->input('filter')) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month);
                    break;
                case 'custom':
                    if ($request->has('start_date') && $request->has('end_date')) {
                        $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date')]);
                    }
                    break;
            }
        }

        $orders = $query->orderBy('id', 'desc')->get();

        // Calculate earnings and finished orders based on the filter
        $earnings = $query->where('status', 'delivered')->sum('total_amount');
        $finishedOrders = $query->where('status', 'delivered')->count();

        return view('admin.salesReport.walkIn', compact('orders', 'earnings', 'finishedOrders'));
    }

    public function indexOnline(Request $request)
    {
        $query = Order::whereNotNull('user_id');

        if ($request->has('filter')) {
            switch ($request->input('filter')) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month);
                    break;
                case 'custom':
                    if ($request->has('start_date') && $request->has('end_date')) {
                        $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date')]);
                    }
                    break;
            }
        }

        $orders = $query->orderBy('id', 'desc')->get();

        // Calculate earnings and finished orders based on the filter
        $earnings = $query->where('status', 'delivered')->sum('total_amount');
        $finishedOrders = $query->where('status', 'delivered')->count();

        return view('admin.salesReport.onlineTransaction', compact('orders', 'earnings', 'finishedOrders'));
    }
}
