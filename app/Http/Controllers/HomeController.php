<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Ingredient;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // File: app/Http/Controllers/HomeController.php

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
        $totalSalesSum = $totalSales->sum(); // Calculate the total sales sum

        $lowStockProducts = Product::whereColumn('stock', '<', 'low_stock_threshold')->get();
        $lowStockIngredients = Ingredient::whereColumn('stock', '<', 'low_stock_threshold')->get();

        return view('home', compact('products', 'productNames', 'totalSales', 'totalSalesSum', 'lowStockProducts', 'lowStockIngredients'));
    }
}
