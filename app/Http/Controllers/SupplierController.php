<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::all();

       return view ('suppliers.index', compact('suppliers'));
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
            'name' => 'required',
            'tin' => 'required',
            'vrn' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            // 'department_id' => 'required',
        ]);

        $name = ucwords($request['name']);
        $tin = ucwords($request['tin']);
        $vrn = ucwords($request['vrn']);
        $address = ucwords($request['address']);
   //   $email = ucwords($request['email']);
        $phone = ucwords($request['phone']);

        $supplier = new Supplier();
        $supplier->name = $name;
        $supplier->tin_number = $tin;
        $supplier->vrn = $vrn;
        $supplier->address = $address;
        $supplier->email = $request['email'];
        $supplier->phone = $phone;
        $supplier->status = 1;
        //$supplier->created_by =Auth::user()->role_id;
        $supplier->save();
        return redirect()->route('suppliers.index')->with('success','Supplier added successfully.');
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
            'tin' => 'required',
            'vrn' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email',
            'address' => 'required',
            'status' => 'required',
            // 'department_id' => 'required',
        ]);

        $name = ucwords($request['name']);
        $tin = ucwords($request['tin']);
        $vrn = ucwords($request['vrn']);
        $phone = ucwords($request['phone']);
   //   $email = ucwords($request['email']);
        $address = ucwords($request['address']);

        $supplier = Supplier::find($id);
        //$customer_createdBy= $customer->created_by;
        //$customer = new Customer();
        $supplier->name = $name;
        $supplier->tin_number = $tin;
        $supplier->vrn = $vrn;
        $supplier->phone = $phone;
        $supplier->email = $request['email'];
        $supplier->address = $address;
        $supplier->status = $request['status'];
        $supplier->save();
        return redirect()->route('suppliers.index')->with('success','Supplier information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully!');
    }
}
