<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStockHistory;

class ProductStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = ProductStockHistory::all();
        $products =Product::all();
        return view('stocks.index',compact('stocks','products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
        ]);

        $product_id = $request['product_id'];
        $quantity =$request['quantity'];

        $stock =  new ProductStockHistory();

        $stock->product_id =  $product_id ;
        $stock->quantity =  $quantity;
        $stock->save();

        $product = Product::find($product_id );
        $product->stock_quantity +=  $quantity;
        $product->save();


        return redirect()->route('stocks.index')->with('success','Product stock added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'quantity'=>'required'
        ]);

        $quantity = $request['quantity'];

        $stock = ProductStockHistory::find($id);
        $previous_added_quantity = $stock->quantity;
        $stock->quantity = $quantity;
        $stock->save();


        $product = $stock->product;


        $difference = $quantity - $previous_added_quantity;

        $product->stock_quantity += $difference;
        $product->save();

        /*   if($previous_added_quantity >  $quantity ){
            $difference = $previous_added_quantity  -  $quantity;
            $product->stock_quantity -=  $difference;
            $product->save();
        }
        else {
            $difference = $quantity - $previous_added_quantity;
            $product->stock_quantity +=  $difference;
            $product->save();
        } */

        return redirect()->route('stocks.index')->with('success',' stock Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
