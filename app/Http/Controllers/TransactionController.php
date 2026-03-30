<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::orderBy('date','desc')->get();

        return view("transaction.index",compact('transactions'));
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
         $request->validate([
            'description' => 'required|string',
            'dr' => 'required|string',
            'cr' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $description = $request['description'];
        $dr = $request['dr'];
        $cr = $request['cr'];
        $amount = $request['amount'];
        $date = $request['date'];

        $transaction = new Transaction();
        $transaction->description = $description;
        $transaction->dr = $dr;
        $transaction->cr = $cr;
        $transaction->amount = $amount;
        $transaction->date = $date;
        $transaction->save();
        return redirect()->route('transactions.index')->with('success','Transaction added Successfuly!');
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
        $request->validate([
            'description' => 'required|string',
            'dr' => 'required|string',
            'cr' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $description = $request['description'];
        $dr = $request['dr'];
        $cr = $request['cr'];
        $amount = $request['amount'];
        $date = $request['date'];

        $transaction = Transaction::findOrFail($id);

        $transaction->description = $description;
        $transaction->dr = $dr;
        $transaction->cr = $cr;
        $transaction->amount = $amount;
        $transaction->date = $date;
        $transaction->save();
        return redirect()->route('transactions.index')->with('success','Transaction Updated Successfuly!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success','Transaction Updated Successfuly!');
    }
}
