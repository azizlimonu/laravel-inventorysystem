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

        $request->validated();

        $request['slug'] = $this->create_slug($request['name']);

        Category::create($request->all());

        return response([
            'message' => 'Se creo la categoría'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $term)
    {
        $category = Category::where('id', $term)
            ->orWhere('slug', $term)
            ->get();

        if ( count($category) == 0 ) {
            return response()->json([
                'message' => 'No se encontro la categoría'
            ], 404);
        }

        return $category[0];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);

        if ( !$category ) {
            return response()->json([
                'message' => 'No se encontro la categoría'
            ], 404);
        }

        // $category = $this->show($id);

        $request['slug'] = $this->create_slug($request['name']);
        $category->update($request->all());

        return response([
            'message' => 'La categoria fue actualizada'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        
        if ( !$category ) {
            return response()->json([
                'message' => 'No se encontro la categoría'
            ], 404);
        }

        $category->delete();
        return response([
            'message' => 'La categoria fue eliminada'
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
