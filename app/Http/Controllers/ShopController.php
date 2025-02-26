<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categories = Category::all();
        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $products = $query->with('category')->paginate(10);
        return view('guest.shop.index', compact('categories', 'products'));
    }

    public function filter(Request $request)
    {
        $categoryId = $request->input('category_id');
        $search = $request->input('search');

        $categories = Category::all();
        $query = Product::query();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $products = $query->with('category')->paginate(9);

        return view('guest.shop', compact('categories', 'products'));
    }

}
