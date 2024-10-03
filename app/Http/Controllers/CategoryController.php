<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function indexBlade()
    {
        return view('admin.category.index');
    }

    public function dataTable()
    {
        $search = request()->input('search.value');
        $categories = Category::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })->get();

        return response()->json([
            'draw' => intval(request()->input('draw')),
            'recordsTotal' => Category::count(),
            'recordsFiltered' => $categories->count(),
            'data' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $category = Category::create($request->all());
            return response()->json(['message' => 'Category has been added.', 'data' => $category], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while adding the category.'], 500);
        }
    }

    public function edit(string $id)
    {
        try {
            $category = Category::find($id);
            return response()->json(['data' => $category]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while editing the category.'], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $category = Category::find($id);
            $category->update($request->all());
            return response()->json(['message' => 'Category has been updated.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the category.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $category = Category::find($id);
            $category->delete();
            return response()->json(['message' => 'Category has been deleted.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the category.'], 500);
        }
    }
}
