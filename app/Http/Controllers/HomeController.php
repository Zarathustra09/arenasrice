<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductContainer;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Ingredient;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


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
            $query->whereHas('order', function ($query) {
                $query->where('status', 'delivered');
            });
        }]);

        $products = $query->get()->map(function ($product) {
            $product->total_sales = $product->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->price;
            });
            return $product;
        })->sortByDesc('total_sales')->take(10);

        $todaysSales = Order::whereDate('created_at', today())
            ->where('status', 'delivered')
            ->sum('total_amount');

        $weeklySales = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'delivered')
            ->sum('total_amount');

        $monthlyEarnings = Order::whereBetween('created_at', [now()->startOfMonth(), now()])
            ->where('status', 'delivered')
            ->sum('total_amount');

        $annualEarnings = Order::whereBetween('created_at', [now()->startOfYear(), now()])
            ->where('status', 'delivered')
            ->sum('total_amount');

        $pendingOrders = Order::where('status', 'pending')->count();

        $productNames = $products->pluck('name');
        $totalSales = $products->pluck('total_sales');
        $totalSalesSum = $totalSales->sum();

        $lowStockProducts = Product::whereColumn('stock', '<', 'low_stock_threshold')->get();
        $lowStockIngredients = Ingredient::whereColumn('stock', '<', 'low_stock_threshold')->get();
        $lowStockContainers = ProductContainer::whereColumn('quantity', '<', 'low_stock_threshold')->get();


        $lowStockProductsCount = Product::whereColumn('stock', '<', 'low_stock_threshold')->count();
        $lowStockIngredientsCount = Ingredient::whereColumn('stock', '<', 'low_stock_threshold')->count();
        $lowStockContainersCount = ProductContainer::whereColumn('quantity', '<', 'low_stock_threshold')->count();

        $nullUserOrders = Order::whereNull('user_id')->get();
        $nullUserOrderSales = $nullUserOrders->sum('total_amount');

        return view('home', compact(
            'products', 'productNames', 'totalSales', 'totalSalesSum', 'lowStockProducts', 'lowStockIngredients',
            'todaysSales', 'monthlyEarnings', 'annualEarnings', 'pendingOrders',
            'lowStockProductsCount', 'lowStockIngredientsCount', 'lowStockContainersCount', 'lowStockContainers',
            'nullUserOrderSales', 'weeklySales'
        ));
    }
}
