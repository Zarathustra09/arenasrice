<?php

namespace App\Http\Controllers;

use App\Models\ProductContainer;
use Illuminate\Http\Request;

class ProductContainerController extends Controller
{
    public function index()
    {
        $containers = ProductContainer::all();
        return view('admin.container.index', compact('containers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'low_stock_threshold' => 'required|integer',
        ]);

        ProductContainer::create($request->only(['name', 'quantity', 'low_stock_threshold']));

        return response()->json(['message' => 'Container has been added.'], 201);
    }

    public function show($id)
    {
        $container = ProductContainer::findOrFail($id);
        return response()->json($container);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'low_stock_threshold' => 'required|integer',
        ]);

        $container = ProductContainer::findOrFail($id);
        $container->update($request->only(['name', 'quantity', 'low_stock_threshold']));

        return response()->json(['message' => 'Container has been updated.'], 200);
    }

    public function destroy($id)
    {
        $container = ProductContainer::findOrFail($id);
        $container->delete();

        return response()->json(['message' => 'Container has been deleted.'], 200);
    }
}
