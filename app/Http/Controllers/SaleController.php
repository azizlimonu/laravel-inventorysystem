<?php

namespace App\Http\Controllers;

use App\Http\Resources\SaleCollection;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new SaleCollection( Sale::all() );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Generar venta
        $sale = new Sale();

        $sale->client = $request->client;
        $sale->total = $request->total;
        $sale->save();

        // Obtener el arreglo de productos
        $products = $request->products;
        $product_sale = [];

        // Iteramos los productos de la request
        foreach ($products as $product)
        {
            $product_sale[] = [
                'sale_id' => $sale['id'],
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'created_At' => Carbon::now(),
                'updated_At' => Carbon::now(),
            ];


            // Actualizar stock de cada producto
            $product_updated = Product::find($product['id']);

            if( $product['quantity'] > $product_updated['stock'] )
            {
                return response(['meessage' => 'No hay stock suficiente'], 400);
            }

            $product_updated['stock'] = $product_updated['stock'] - $product['quantity'];
            $product_updated->update();
        }

        ProductSale::insert($product_sale);

        return response(['meessage' => 'Venta realizada con exito']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
}
