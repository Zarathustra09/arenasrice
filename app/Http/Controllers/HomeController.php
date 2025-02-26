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
        $query = Product::with(['orderItems' => function ($query) use ($request) {
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

        $products = $query->get()->map(function ($product) {
            $product->total_sales = $product->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->price;
            });
            return $product;
        })->sortByDesc('total_sales')->take(10);

        $productNames = $products->pluck('name');
        $totalSales = $products->pluck('total_sales');
        $lowStockProducts = Product::where('stock', '<', 20)->get();

        return view('home', compact('products', 'productNames', 'totalSales', 'lowStockProducts'));
    }


    public function lowStockData()
    {
        $products = Product::where('stock', '<', 20)->get();

        return response()->json($products);
    }
}
