<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IngredientController extends Controller
{
    public function index()
    {
        return view('admin.ingredients.index');
    }


    // File: app/Http/Controllers/IngredientController.php

    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:255|unique:ingredients,sku',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'required|integer',
            'low_stock_threshold' => 'required|integer', // Add this line
            'image' => 'nullable|image'
        ]);

        try {
            $data = $request->all();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images', 'public');
            }
            $ingredient = Ingredient::create($data);
            return response()->json(['message' => 'Ingredient has been added.', 'data' => $ingredient], 201);
        } catch (\Exception $e) {
            Log::error('Error adding ingredient: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while adding the ingredient.'], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'sku' => 'required|string|max:255|unique:ingredients,sku,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'required|integer',
            'low_stock_threshold' => 'required|integer', // Add this line
            'image' => 'nullable|image'
        ]);

        try {
            $ingredient = Ingredient::find($id);
            $data = $request->all();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images', 'public');
            }
            $ingredient->update($data);

            return response()->json(['message' => 'Ingredient has been updated.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the ingredient.'], 500);
        }
    }

    public function dataTable()
    {
        $search = request()->input('search.value');
        $ingredients = Ingredient::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->get();

        return response()->json([
            'draw' => intval(request()->input('draw')),
            'recordsTotal' => $ingredients->count(),
            'recordsFiltered' => $ingredients->count(),
            'data' => $ingredients
        ]);
    }



    public function show(string $id)
    {
        try {
            $ingredient = Ingredient::find($id);
            return response()->json(['data' => $ingredient]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while retrieving the ingredient.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $ingredient = Ingredient::find($id);
            $ingredient->delete();

            return response()->json(['message' => 'Ingredient has been deleted.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the ingredient.'], 500);
        }
    }
}
