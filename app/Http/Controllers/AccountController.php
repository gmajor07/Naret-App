<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Company;
use App\Models\Currency;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::all();
        $companies = Company::all();
        $currencies = Currency::all();
        return view('accounts.index',compact('accounts','companies','currencies'));
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
            'number' => 'required',
            'currency_id' => 'required',
            'company_id' => 'required',
            'institution' => 'required',
        ]);

        $name = ucwords($request['name']);
        $swift = strtoupper($request['swift']);
        $institution =strtoupper($request['institution']);

        $account = new Account();

        $account->swift_code = $swift;
        $account->financial_institution = $institution;
        $account->branch = $request['branch'];
        $account->account_name = $name;
        $account->account_number = $request['number'];
        $account->currency_id = $request['currency_id'];
        $account->company_id =  $request['company_id'];
        $account->save();

        return redirect()->route('accounts.index')->with('success','Account added successfully');

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
            'number' => 'required',
            'currency_id' => 'required',
            'company_id' => 'required',
            'institution' => 'required',
        ]);

        $name = ucwords($request['name']);
        $swift = strtoupper($request['swift']);
        $institution =strtoupper($request['institution']);

        $account = Account::find($id);

        $account->swift_code = $swift;
        $account->financial_institution = $institution;
        $account->branch = $request['branch'];
        $account->account_name = $name;
        $account->account_number = $request['number'];
        $account->currency_id = $request['currency_id'];
        $account->company_id =  $request['company_id'];
        $account->save();

        return redirect()->route('accounts.index')->with('success','Account added successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully!');
    }
}
