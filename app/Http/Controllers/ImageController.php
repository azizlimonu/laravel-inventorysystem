<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ImageController extends Controller
{
    public function store(Request $request)
    {
        $image = $request->file('image');

        $name = $image->getClientOriginalName();
        $path = $request->image->storeAs('public/products', $name);
        return Storage::url($path);
    }
}
