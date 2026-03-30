<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();
        return view('company.index',compact('companies'));
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

        ]);

        $name = ucwords($request['name']);
        $description = ucwords($request['description']);

        $company = new Company();

        $company->name = $name;
        $company->description = $description;
        $company->save();

        return redirect()->route('company.index')->with('success','Company added successfully');

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

        ]);

        $name = ucwords($request['name']);
        $description = ucwords($request['description']);

        $company = Company::find($id);

        $company->name = $name;
        $company->description = $description;
        $company->save();

        return redirect()->route('company.index')->with('success','Company added successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::findOrFail($id);
        
        foreach ($company->accounts as $account) {
            $account->delete();
        }
        $company->delete();

        return redirect()->route('company.index')->with('success', 'Company deleted successfully!');
    }
}
