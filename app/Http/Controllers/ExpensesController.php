<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Expense;
use Illuminate\Http\Request;
use App\Exports\ExpensesExport;
use Maatwebsite\Excel\Facades\Excel;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::orderBy('date','desc')->get();
        return view('expenses.index',compact('expenses'));
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
            'description' => 'required',
            'amount' => 'required',
            'expense_date'=>'required|date',

        ]);

        $amount = $request['amount'];
        $description = ucwords($request['description']);
        $expense_date = Carbon::parse($validatedData['expense_date'])->format('Y-m-d');

        $expense = new Expense();

        $expense->description = $description;
        $expense->amount = $amount;
        $expense->date =  $expense_date;
        $expense->save();

        return redirect()->route('expenses.index')->with('success','Expenditure added successfully');
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
            'description' => 'required',
            'amount' => 'required',
            'expense_date'=>'required|date',

        ]);

        $amount = $request['amount'];
        $description = ucwords($request['description']);
        $expense_date = Carbon::parse($validatedData['expense_date'])->format('Y-m-d');

        $expense = Expense::find($id);

        $expense->description = $description;
        $expense->amount = $amount;
        $expense->date =  $expense_date;
        $expense->save();

        return redirect()->route('expenses.index')->with('success','Expenditure Updated successfully');
    }

    public function exportExpenses(Request $request)
    {
          // Validate user input
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $fileName = 'expenses_' . $request->start_date . '_to_' . $request->end_date . '.xlsx';

            // Export data using the ExpensesExport class
        return Excel::download(new ExpensesExport($request->start_date, $request->end_date), $fileName);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expenditure deleted successfully!');
    }
}
