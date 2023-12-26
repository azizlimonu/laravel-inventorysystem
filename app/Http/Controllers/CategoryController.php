<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        // Validate the request using the specified form request
        $request->validated();

        // Create a slug for the category based on its name
        $request['slug'] = $this->create_slug($request['name']);

        // Store the category in the database
        Category::create($request->all());

        return response([
            'message' => 'Category created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $term)
    {
        // Find a category by ID or slug
        $category = Category::where('id', $term)
            ->orWhere('slug', $term)
            ->get();

        if (count($category) == 0) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        return $category[0];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the category by ID
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        // Update the category and create a new slug
        $request['slug'] = $this->create_slug($request['name']);
        $category->update($request->all());

        return response([
            'message' => 'Category updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the category by ID
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        // Delete the category
        $category->delete();
        return response([
            'message' => 'Category deleted successfully'
        ]);
    }

    /**
     * Create a slug from the given text.
     */
    function create_slug($text)
    {
        $text = strtolower($text);

        // Regular expressions
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');

        $text = preg_replace('/-+/', '-', $text);

        return $text;
    }
}
