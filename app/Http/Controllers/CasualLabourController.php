<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Type;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CasualLabour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CasualLabourExport;

class CasualLabourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::where('type_id', 3)->get();
        $customers = Customer::all();
        $currencies  = Currency::all();
        //$casual_labour = CasualLabour::all();
        return view('casual_labour.index',compact('orders','customers','currencies'));
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
        $validatedData= $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'description.*' => 'required|string|max:255',
            'labour_charges.*' => 'required|integer|min:1',
            'administration_fee.*' => 'required|integer|min:0',
            'quantity.*' => 'required|integer|min:1',
            'po_number' => 'nullable|string|max:255',
            'apply_vat2' => 'nullable|boolean',
            'withholding2' => 'nullable|boolean',
            'discount_amount2' => 'nullable|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'date' => 'required|date',

            //'fumigationsArr' => 'required|array'
        ]);
        $date = Carbon::parse($validatedData['date'])->format('Y-m-d');

        DB::transaction(function() use($validatedData, $request,$date){

            $casual_labour = [];
            //$total_adminitration_fee=0;

            foreach($validatedData['description'] as $key => $description){

                $expense = new Expense();
                $expense->description = $description;
                $expense->amount =  $request['labour_charges'][$key] * $request['quantity'][$key];
                $expense->date = $date;
                $expense->save();


                $casual = new CasualLabour();

                $casual->description = $description;
                $casual->labour_charge =$request['labour_charges'][$key];
                $casual->administration_fee =$request['administration_fee'][$key];
                $casual->quantity =$request['quantity'][$key];
                $total = ($request['labour_charges'][$key] + $request['administration_fee'][$key])* $request['quantity'][$key];


                if($request->discount_amount2){
                    if($request->apply_vat2 == 1){
                        $casual->vat = ($total - $request->discount_amount2) * 0.18;
                        $casual->payable_amount = ($total - $request->discount_amount2) + $casual->vat;
                    }
                    else{
                        $casual->vat = 0;
                        $casual->payable_amount = ($total - $request->discount_amount2);
                    }

                 }
                 else{
                      if($request->apply_vat2 == 1){
                        $casual->vat = $total * 0.18;
                        $casual->payable_amount = $total + $casual->vat ;
                      }
                      else{
                        $casual->vat =0;
                        $casual->payable_amount = $total;
                      }

                 }




                $casual->status = 0;
                // Save Expense ID in Casual Labour
                $casual->expense_id = $expense->id;
                $casual->date = $date;
                $casual->save();


                $casual_labour[] = $casual;

            }


            $type = Type::where('name','=','casual')->value('id');

            $order = new Order();
            $order->customer_id = $request['customer_id'];
            $order->type_id = $type;
            $order->order_date = $date;
            $order->status = 0;
            $order->po_number = $request->input('po_number');
            $order->save();

            foreach ($casual_labour as $casual) {
              $order->casual_labour()->attach($casual->id, ['created_at' => now(), 'updated_at' => now()]);
            }

            $totalAmount = 0;
            foreach ($validatedData['labour_charges'] as $key => $labour) {
                $totalAmount += ($labour + $validatedData['administration_fee'][$key]) * $validatedData['quantity'][$key] ;
            }

            $invoice = new Invoice();
            $invoice->customer_id = $order->customer_id;
            $invoice->order_id = $order->id;

            if($request->input('discount_amount2')){
                $invoice->discount = $request['discount_amount2'];
                if( $request->input('apply_vat2') ==1 ){
                    $invoice->total_amount = $totalAmount;
                    $invoice->total_vat_inclusive = ($totalAmount-$invoice->discount) + (($totalAmount-$invoice->discount) * 0.18);
                    $invoice->vat = ($totalAmount-$invoice->discount) * 0.18;
                    $invoice->payable_amount = $invoice->total_vat_inclusive;
                    $invoice->amount_due=$invoice->total_vat_inclusive;
                    $invoice->total_vat_exclusive = 0;
                }else{
                    $invoice->total_vat_inclusive = 0;
                    $invoice->vat = 0;
                    $invoice->total_amount = $totalAmount;
                    $invoice->payable_amount = $totalAmount-$invoice->discount;
                    $invoice->amount_due=$totalAmount-$invoice->discount;
                    $invoice->total_vat_exclusive = $totalAmount-$invoice->discount;
                }
            }
            else{
                $invoice->discount = 0;
                if( $request->input('apply_vat2') ==1 ){
                    $invoice->total_amount = $totalAmount;
                    $invoice->total_vat_inclusive = $totalAmount + ($totalAmount * 0.18);
                    $invoice->vat = $totalAmount * 0.18;
                    $invoice->payable_amount = $invoice->total_vat_inclusive;
                    $invoice->amount_due=$invoice->total_vat_inclusive;
                    $invoice->total_vat_exclusive = 0;
                }else{
                    $invoice->total_vat_inclusive = 0;
                    $invoice->vat = 0;
                    $invoice->total_amount = $totalAmount;
                    $invoice->payable_amount = $totalAmount;
                    $invoice->amount_due=$totalAmount;
                    $invoice->total_vat_exclusive = $totalAmount;
                }

            }
            logger('withholding2: ' . json_encode($request->withholding2));
            if((int)$request->withholding2 == 1){
                $invoice->withholding_tax = $invoice->payable_amount * 0.05;
                $invoice->payable_amount = $invoice->payable_amount - $invoice->withholding_tax;
            }
            else{
                $invoice->withholding_tax = 0;
            }
            $invoice->amount_paid=0;
            $invoice->payment_status =0;
            $invoice->invoice_status =0;
            $invoice->currency_id = $request['currency_id'];
            $invoice->invoice_type = 3;
            $invoice->due_date=now()->addDays(7);
            $invoice->save();

        });
        return redirect()->route('casual_labour.index')->with('success', 'Casual Labour placed successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //$casual = CasualLabour::findOrFail($id);
        $order = Order::findOrfail($id);

        return view('casual_labour.view',compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id){

        $order = Order::findOrFail($id);

        return view('casual_labour.edit',compact('order'));
    }

    /**
     * Show the form for back the specified resource.
     */
    public function casual_control_back_button()
    {
        return redirect()->route('casual_labour.index');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'casual_id.*' => 'nullable|exists:casual_labours,id',
            'description.*' => 'required|string|max:255',
            'labour_charges.*' => 'required|integer|min:1',
            'administration_fee.*' => 'required|integer|min:0',
            'quantity.*' => 'required|integer|min:1',
            'po_number' => 'nullable|string|max:255',
            'date' => 'required|date',
            'apply_vat' => 'nullable|boolean',
            'withholding' => 'nullable|boolean',
            'apply_discount'=> 'nullable|boolean',
            'discount_amount' => 'nullable|numeric|min:0',
            //'currency_id' => 'required|exists:currencies,id',
        ]);

        $order = Order::findOrFail($id);
        DB::transaction(function () use ($validatedData, $request, $order) {
            $selectedDate = Carbon::parse($validatedData['date']);

            // Detach and delete existing casual labours
            $existingCasuals = $order->casual_labour;
            //$existingExpenses = $order->casual_labour->expense;
            $existingExpenses = Expense::whereIn('id', $order->casual_labour->pluck('expense_id'))->get();
            $order->casual_labour()->detach();

            $order->customer_id = $request['customer_id'];
            $order->order_date = $selectedDate;
            $order->created_at = $selectedDate;
            $order->po_number = $request->input('po_number');
            $order->save();

            $casual_labours = [];

            foreach ($validatedData['description'] as $key => $desc) {
                $casualId = $validatedData['casual_id'][$key] ?? null;

                $expenseAmount = $validatedData['labour_charges'][$key] * $validatedData['quantity'][$key];
                $expense = new Expense();
                $expense->description = $desc." order number ".$order->order_number;
                $expense->amount = $expenseAmount;
                $expense->date = $selectedDate->format('Y-m-d');
                $expense->save();

                if ($casualId && CasualLabour::find($casualId)) {
                    $casual = CasualLabour::find($casualId);
                } else {
                    $casual = new CasualLabour();
                }

                $casual->description = $desc;
                $casual->labour_charge = $validatedData['labour_charges'][$key];
                $casual->administration_fee = $validatedData['administration_fee'][$key];
                $casual->quantity = $validatedData['quantity'][$key];
                $total = ($casual->labour_charge + $casual->administration_fee) * $casual->quantity;

                if ($request->apply_discount) {
                    if ($request->apply_vat2 == 1) {
                        $casual->vat = ($total - $request->discount_amount2) * 0.18;
                        $casual->payable_amount = ($total - $request->discount_amount2) + $casual->vat;
                    } else {
                        $casual->vat = 0;
                        $casual->payable_amount = $total - $request->discount_amount2;
                    }
                } else {
                    if ($request->apply_vat2 == 1) {
                        $casual->vat = $total * 0.18;
                        $casual->payable_amount = $total + $casual->vat;
                    } else {
                        $casual->vat = 0;
                        $casual->payable_amount = $total;
                    }
                }

                $casual->status = 0;
                $casual->expense_id = $expense->id;
                $casual->date = $selectedDate->format('Y-m-d');
                $casual->save();

                $order->casual_labour()->attach($casual->id, ['created_at' => $selectedDate, 'updated_at' => now()]);
                $casual_labours[] = $casual;
            }

            foreach ($existingCasuals as $oldCasual) {
                $oldCasual->delete(); // optional: only delete if you don’t want to retain history
            }

            foreach ($existingExpenses as $oldExpense) {
                $oldExpense->delete(); // optional: only delete if you don’t want to retain history
            }

            // Update invoice
            $totalAmount = 0;
            foreach ($casual_labours as $casual) {
                $totalAmount += ($casual->labour_charge + $casual->administration_fee) * $casual->quantity;
            }

            $invoice = $order->invoice;
            //$invoice->discount = $request->discount_amount2 ?? 0;

            if ($request->input('discount_amount')) {
                $invoice->discount = $request->input('discount_amount');
                if ($request->input('apply_vat') == 1) {
                    $invoice->total_vat_inclusive = ($totalAmount - $invoice->discount) + (($totalAmount - $invoice->discount) * 0.18);
                    $invoice->vat = ($totalAmount - $invoice->discount) * 0.18;
                    $invoice->payable_amount = $invoice->total_vat_inclusive;
                    $invoice->amount_due = $invoice->total_vat_inclusive;
                    $invoice->total_vat_exclusive = 0;
                } else {
                    $invoice->total_vat_inclusive = 0;
                    $invoice->vat = 0;
                    $invoice->payable_amount = $totalAmount-$invoice->discount;
                    $invoice->amount_due = $totalAmount - $invoice->discount;
                    $invoice->total_vat_exclusive = $totalAmount - $invoice->discount;
                }
            } else {
                $invoice->discount = 0;
                if ($request->input('apply_vat') == 1) {
                    $invoice->total_vat_inclusive = $totalAmount + ($totalAmount * 0.18);
                    $invoice->vat = $totalAmount * 0.18;
                    $invoice->payable_amount = $invoice->total_vat_inclusive;
                    $invoice->amount_due = $invoice->total_vat_inclusive;
                    $invoice->total_vat_exclusive = 0;
                } else {
                    $invoice->total_vat_inclusive = 0;
                    $invoice->vat = 0;
                    $invoice->payable_amount = $totalAmount;
                    $invoice->amount_due = $totalAmount;
                    $invoice->total_vat_exclusive = $totalAmount;
                }
            }

            if($request->withholding == 1){
                $invoice->withholding_tax = $invoice->payable_amount * 0.05;
            }
            else{
              $invoice->withholding_tax = 0;
            }

            //$invoice->currency_id = $request->currency_id;
            $invoice->customer_id = $order->customer_id;
            $invoice->created_at = $selectedDate;
            $invoice->due_date = $selectedDate->copy()->addDays(7);
            $invoice->save();
        });

        return redirect()->route('casual_labour.index')->with('success', 'Casual Labour updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            $order = Order::findOrFail($id);

            // Collect all related casual labour records
            $casualLabours = $order->casual_labour;

            // Delete related expenses
            $expenseIds = $casualLabours->pluck('expense_id')->filter()->unique();
            Expense::whereIn('id', $expenseIds)->delete();

            // Delete casual_labour records
            foreach ($casualLabours as $casual) {
                $casual->delete();
            }

            // Delete invoice
            $order->invoice?->delete();

            // Finally, delete the order
            $order->delete();
        });

        return redirect()->route('casual_labour.index')->with('success', 'Order deleted successfully!');
    }

    public function export(Request $request)
    {
        $exportType = $request->input('export_type');
        $startDate = null;
        $endDate = null;

        switch ($exportType) {
            case 'all':
                // No date filter
                break;
            case 'custom':
                $startDate = $request->input('start_date');
                $endDate = $request->input('end_date');
                break;
            case 'this_month':
                $startDate = now()->startOfMonth()->toDateString();
                $endDate = now()->endOfMonth()->toDateString();
                break;
            case 'last_month':
                $startDate = now()->subMonth()->startOfMonth()->toDateString();
                $endDate = now()->subMonth()->endOfMonth()->toDateString();
                break;
            case 'this_week':
                $startDate = now()->startOfWeek()->toDateString();
                $endDate = now()->endOfWeek()->toDateString();
                break;
            case 'last_week':
                $startDate = now()->subWeek()->startOfWeek()->toDateString();
                $endDate = now()->subWeek()->endOfWeek()->toDateString();
                break;
            case 'this_year':
                $startDate = now()->startOfYear()->toDateString();
                $endDate = now()->endOfYear()->toDateString();
                break;
            case 'last_year':
                $startDate = now()->subYear()->startOfYear()->toDateString();
                $endDate = now()->subYear()->endOfYear()->toDateString();
                break;
            default:
                // Default to all
                break;
        }

        return Excel::download(new CasualLabourExport($startDate, $endDate), 'casual_labour_orders.xlsx');
    }



}
