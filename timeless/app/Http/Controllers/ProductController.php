<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;  // Import the Category model
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // Return all products with their associated categories
        $products = Product::with('categories')->get();

        // Add the image URL to each product's response data
        foreach ($products as $product) {
            if ($product->image_url) {
                // Generate the full URL to the image
                $product->image_url = asset('storage/' . $product->image_url);
            }
        }

        return response()->json($products);
    }

    public function store(Request $request)
    {
        // Validate the input data, including the image upload (if present)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity_in_stock' => 'required|integer',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        // Handle file upload if there is an image
        if ($request->hasFile('image_url')) {
            // Store the image in the 'products' directory under 'storage/app/public'
            $originalFileName = $request->file('image_url')->getClientOriginalName();
            $imagePath = $request->file('image_url')->storeAs('products', $originalFileName, 'public');
        } else {
            $imagePath = null; // If no image is provided, set the imagePath to null
        }

        // Create the new product record in the database
        $product = Product::create([
            'name' => $validatedData['name'],
            'brand' => $validatedData['brand'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'quantity_in_stock' => $validatedData['quantity_in_stock'],
            'image_url' => $imagePath, // Store the image path in the 'image_url' field
        ]);

        // Attach categories if provided
        if (isset($validatedData['category_ids'])) {
            $product->categories()->sync($validatedData['category_ids']);
        }

        // Return the created product with the full image URL in the response
        if ($product->image_url) {
            $product->image_url = asset('storage/products/' . $product->image_url);
        }

        return response()->json($product, 201);
    }

    public function show($id)
    {
        return Product::with('categories')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        // Find the product by ID
        $product = Product::findOrFail($id);

        // Validate input data, including the image upload (if present)
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'quantity_in_stock' => 'nullable|integer',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image if present
            'category_ids' => 'nullable|array',  // Ensure category_ids is an array
            'category_ids.*' => 'exists:categories,id', // Validate each category_id exists in the categories table
        ]);

        // Handle file upload if there is an image
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('products', 'public');
        } else {
            $imagePath = $product->image_url; // Keep existing image if none is uploaded
        }

        // Update the product
        $product->update([
            'name' => $validatedData['name'] ?? $product->name,
            'brand' => $validatedData['brand'] ?? $product->brand,
            'description' => $validatedData['description'] ?? $product->description,
            'price' => $validatedData['price'] ?? $product->price,
            'quantity_in_stock' => $validatedData['quantity_in_stock'] ?? $product->quantity_in_stock,
            'image_url' => $imagePath,
        ]);

        // Attach categories to the product (if category_ids is provided)
        if (isset($validatedData['category_ids'])) {
            $product->categories()->sync($validatedData['category_ids']);
        }

        return response()->json($product);
    }

    public function destroy($id)
    {
        // Delete the product and its associated categories
        $product = Product::findOrFail($id);
        $product->categories()->detach();  // Remove the product's category associations
        $product->delete(); // Delete the product
        return response()->json(null, 204);
    }
}
