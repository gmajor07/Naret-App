<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();

       return view ('customers.index', compact('customers'));
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
            'phone' => 'required',
            'email' => 'required|email|unique:users,email',
            'location' => 'required',
            // 'department_id' => 'required',
        ]);

        $name = ucwords($request['name']);
        $tin = ucwords($request['tin']);
        $vrn = ucwords($request['vrn']);
        $phone = ucwords($request['phone']);
   //   $email = ucwords($request['email']);
        $location = ucwords($request['location']);

        $customer = new Customer();
        $customer->name = $name;
        $customer->tin_number = $tin;
        $customer->vrn = $vrn;
        $customer->phone = $phone;
        $customer->email = $request['email'];
        $customer->location = $location;
        $customer->status = 1;
        $customer->created_by =Auth::user()->role_id;
        $customer->save();
        return redirect()->route('customers.index')->with('success','Customer added successfully.');

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
            'location' => 'required',
            'status' => 'required',
            // 'department_id' => 'required',
        ]);

        $name = ucwords($request['name']);
        $tin = ucwords($request['tin']);
        $vrn = ucwords($request['vrn']);
        $phone = ucwords($request['phone']);
   //   $email = ucwords($request['email']);
        $location = ucwords($request['location']);

        $customer = Customer::find($id);
        $customer_createdBy= $customer->created_by;
        //$customer = new Customer();
        $customer->name = $name;
        $customer->tin_number = $tin;
        $customer->vrn = $vrn;
        $customer->phone = $phone;
        $customer->email = $request['email'];
        $customer->location = $location;
        $customer->status = $request['status'];
        $customer->created_by =$customer_createdBy;
        $customer->save();
        return redirect()->route('customers.index')->with('success','Customer information updated successfully!.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }
}
