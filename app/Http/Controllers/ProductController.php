<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductContainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $containers = ProductContainer::all();
        $products = Product::all();
        return view('admin.product.index', compact('categories', 'containers', 'products'));
    }

//    public function dataTable()
//    {
//        $products = Product::with('category', 'containers')->get();
//
//        return response()->json([
//            'data' => $products
//        ]);
//    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'low_stock_threshold' => 'required|integer',
            'image' => 'nullable|image',
            'container_id' => 'nullable|exists:product_containers,id'
        ]);

        try {
            $data = $request->all();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images', 'public');
            }
            $product = Product::create($data);
            Log::info('Product has been added: ' . $product);
            return response()->json(['message' => 'Product has been added.', 'data' => $product], 201);
        } catch (\Exception $e) {
            Log::error('Error adding product: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while adding the product.'], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'low_stock_threshold' => 'required|integer',
            'image' => 'nullable|image',
            'container_id' => 'nullable|exists:product_containers,id'
        ]);

        try {
            $product = Product::find($id);
            $data = $request->all();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images', 'public');
            }
            $product->update($data);
            Log::info('Product has been updated: ' . $product);
            return response()->json(['message' => 'Product has been updated.'], 200);
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while updating the product.'], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $product = Product::with('category', 'container')->findOrFail($id);

            return response()->json(['data' => $product]);
        } catch (\Exception $e) {
            Log::error('Error retrieving product: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while retrieving the product.'], 500);
        }
    }

    public function edit(string $id)
    {
        try {
            $product = Product::find($id);
            return response()->json(['data' => $product]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while editing the product.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            Log::info('Product has been deleted: ' . $product);
            return response()->json(['message' => 'Product has been deleted.'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while deleting the product.'], 500);
        }
    }
}
