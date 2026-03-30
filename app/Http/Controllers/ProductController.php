<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Type;
use App\Models\Unit_measure;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        $types = Type::all();
        $unit_measures = Unit_measure::all();
        return view('products.index',compact('products','types','unit_measures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getBelowStock()
    {
        $product_below_stock = Product::where('stock_quantity','<', 50)->get();
        $products = Product::all();

        return view('products.belowMargin',compact('product_below_stock','products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required',
            'type_id' => 'required',
            'price' => 'required_if:type_id,1',
            'unit_measure_id' => 'required',
        ]);

        $name = ucwords($request['name']);
        $description = ucwords($request['description']);
        $quantity = strtoupper($request['quantity']);
        $type = $request['type_id'];

        if($request['type_id'] == 1){
            $price = strtoupper($request['price']);
        }
        else{
            $price = 0;
        }


        $product = new  Product();

        $product->name = $name;
        $product->description =$description;
        $product->stock_quantity = $quantity;
        $product->type_id =  $type;
        $product->unity_price =$price;
        $product->unit_measure_id = $request['unit_measure_id'];
        $product->save();

        return redirect()->route('products.index')->with('success','Product added successfully.');

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
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required',
            'unit_measure_id' =>'required',
            'type_id' => 'required',
            'price' => 'required',
        ]);

        $name = ucwords($request['name']);
        $description = ucwords($request['description']);
        $quantity = strtoupper($request['quantity']);
        $price = strtoupper($request['price']);

        $product = Product::find($id);
        $type = $request['type_id'];

        if($request['type_id'] == 1){
            $price = strtoupper($request['price']);
        }
        else{
            $price = 0;
        }


        $product->name = $name;
        $product->description =$description;
        $product->stock_quantity = $quantity;
        $product->unity_price =$price;
        $product->type_id = $type;
        $product->unit_measure_id = $request['unit_measure_id'];
        $product->save();

        return redirect()->route('products.index')->with('success','Product added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
