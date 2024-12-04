<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    public function show($id)
    {
        return Category::find($id);
    }

    public function update(Request $request, $id)
    {
        // Find the product by ID
        $product = Category::findOrFail($id);

        // Validate input data, including the image upload (if present)
        $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Update only the fields that are provided in the request
        if ($request->has('name')) {
            $product->name = $request->input('name');
        }
        if ($request->has('description')) {
            $product->description = $request->input('description');
        
        }

        // Save the product
        $product->save();

        return response()->json(['message' => 'Product updated successfully', 'data' => $product], 200);
    }

    public function destroy($id)
    {
        Category::destroy($id);
        return response()->json(null, 204);
    }
}
