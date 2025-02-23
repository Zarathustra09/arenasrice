<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderItem;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $query = Product::withCount(['orderItems' => function ($query) use ($request) {
            if ($request->has('filter')) {
                switch ($request->filter) {
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
                        $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
                        break;
                }
            }
        }]);

        $products = $query->orderBy('order_items_count', 'desc')->take(10)->get();
        $productNames = $products->pluck('name');
        $orderCounts = $products->pluck('order_items_count');
        $lowStockProducts = Product::where('stock', '<', 20)->get();

        return view('home', compact('products', 'productNames', 'orderCounts', 'lowStockProducts'));
    }


    public function lowStockData()
    {
        $products = Product::where('stock', '<', 20)->get();

        return response()->json($products);
    }
}
