<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new ProductCollection(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $request->validated();

        $request['slug'] = $this->create_slug($request['name']);
        $product = Product::create($request->all());

        return response([
            'message' => 'Create Product Success',
            'product' => $product
        ]);
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $term)
    // {
    //     $product = Product::where('id', $term)
    //         ->orWhere('slug', $term)
    //         ->get()[0];

    //     if (!$product) {
    //         return response()->json([
    //             'message' => 'Theres no product'
    //         ], 404);
    //     }

    //     return new ProductResource($product);
    // }
    public function show($term)
    {
        $product = Product::where('id', $term)
            ->orWhere('slug', $term)
            ->first();

        if (!$product) {
            return response()->json([
                'message' => 'No product found'
            ], 404);
        }

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'No product selected'
            ], 404);
        }

        if ($request['name']) {
            $request['slug'] = $this->create_slug($request['name']);
        }

        $product->update($request->all());

        return response([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'No product selected'
            ], 404);
        }

        $product->delete();
        return response([
            'message' => 'product deleted'
        ]);
    }

    function create_slug($text)
    {
        $text = strtolower($text);

        // Expresiones regulares
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');

        $text = preg_replace('/-+/', '-', $text);

        return $text;
    }
}
