<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::with('category')->paginate(9);
        return view('guest.shop', compact('categories', 'products'));
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
