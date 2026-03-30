<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Account;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use phpDocumentor\Reflection\Types\This;



class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pending_invoices = Invoice::where('invoice_status', 0)->get();
        $partial_paid_invoices = Invoice::where('invoice_status', 1)->get();
        $paid_invoices = Invoice::where('invoice_status', 2)->get();
        $cancelled_invoices = Invoice::where('invoice_status', 3)->get();
        $customers = Customer::all();

         return view('invoices.index', compact('pending_invoices','paid_invoices',
         'partial_paid_invoices','cancelled_invoices','customers'));
    }


    public function addPayment($id){

        $invoice = Invoice::find($id);

        if($invoice->order->type_id == 1){
            return view('invoices.payment', compact('invoice'));
        }
        else if($invoice->order->type_id == 3){
            return view('invoices.casual_payment', compact('invoice'));
        }
        else{
            return view('invoices.fumigation_payment', compact('invoice'));
        }
    }



    public function savePayment(Request $request, $id){
        $request->validate([
            'payment'=>'required|integer|min:0'
        ]);

        DB::transaction(function () use($request,$id){
            $payment = $request['payment'];
            $invoice = Invoice::find($id);
        


                    // Prevent overpayment
                if ($payment > $invoice->amount_due) {
                    abort(400, 'Payment exceeds invoice amount due.');
                }

            
                $sales = new Sale();

                $sales->order_id = $invoice->order_id;
                $sales->invoice_id = $invoice->id;
                $sales->customer_id = $invoice->customer_id;
                $sales->total_amount =  $payment;
                //$sales->stock_already_reduced = 0;
                $sales->rejected = 0;
                $sales->approved_by = 0;
                $sales->save();

        });


        return redirect()->route('invoices.index')->with('success', 'Payment added successfully!');
    }

    //ili iweze kuprint pdf ni lazima table ya  account na company ziwe na data.

    public function printNaretInvoice ($invoice_id, $status1){

        $todays_date = Carbon::now('Africa/Dar_es_Salaam');
        $invoice = Invoice::findOrFail($invoice_id);


        $status = $status1;

        //hakikisha id ya company ni sawa na ya kwenye table ili isilete null
        //$company = Company::with('accounts.currency')->findOrFail(1);
        $company = Company::with('accounts.currency')->firstOrFail();




        $pdf = PDF::loadView('invoicePrint.printNaretCompanyInvoice',compact('invoice','company','status'));
        $pdf->setPaper('A4','portrait');
        return $pdf->download($invoice->customer->name . '_Invoice.pdf');
        //return view('invoicePrint.printNaretCompanyInvoice', compact('invoice', 'status', 'company'));

      }

      public function printNaretFumigationInvonce($invoice_id,$status1)
      {

        $todays_date = Carbon::now('Africa/Dar_es_Salaam');
        $invoice = Invoice::findOrFail($invoice_id);

        $status = $status1;

       //hakikisha id ya company ni sawa na ya kwenye table ili isilete null
        //$company = Company::with('accounts.currency')->findOrFail(2);
        $company = Company::with('accounts.currency')->orderBy('id', 'desc')->firstOrFail();


        $pdf = PDF::loadView('invoicePrint.printNaretFumigationInvoice',compact('invoice','company','status'));
        $pdf->setPaper('A4','portrait');
        return $pdf->download($invoice->customer->name . '_Invoice.pdf');
      }

    public function printCustomInvoiceRecords(Request $request){
         // Validate input
        $validatedData = $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
            'customer_id'      => 'nullable|integer|exists:customers,id',
            'invoice_status'    => 'nullable|integer',
        ]);
        $request->merge([
            'customer_id' => $request->customer_id !== null ? (int) $request->customer_id : null,
            'invoice_status' => $request->invoice_status !== null ? (int) $request->invoice_status : null,
        ]);

        $fromDate = Carbon::parse($validatedData['from_date'])->startOfDay();
        $toDate = Carbon::parse($validatedData['to_date'])->endOfDay();

        $query = Invoice::whereBetween('created_at', [$fromDate, $toDate]);
        //$query = Invoice::whereBetween('created_at', [$validatedData['from_date'], $validatedData['to_date']]);
         // Apply additional filters if provided
        if (!is_null($validatedData['customer_id'])) {
            //$query->where('customer_id', $validatedData['customer_id']); 
            $query->where('customer_id', '=', $validatedData['customer_id']);
        }

        if (!is_null($validatedData['invoice_status'])) {
            $query->where('invoice_status', $request->invoice_status);
        }

            // Clone the query to calculate the total amount before fetching results
        $totalAmount = (clone $query)->sum('payable_amount'); // replace 'amount' with your actual column name
        $remainingDebt = (clone $query)->sum('amount_due'); 

        // Get the filtered invoices
        $invoices = $query->get();

        if($invoices->isEmpty()) {

            return redirect()->route('invoices.index')->with('failuer', 'No Records Found!');
        }

        $pdf = PDF::loadView('reports.printInvoiceReport',compact('invoices','totalAmount','remainingDebt'));
        $pdf->setPaper('A4','landscape');


        $pdfFileName = 'Invoice_Reports_new_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($pdfFileName);
    }

    public function cancel($id)
    {

        DB::transaction(function () use($id){
            $invoice = Invoice::find($id);
            //$order = Order::find($invoice->order_id);
            $invoice->order->status = 3;
            $invoice->order->save();

            /* $invoice->order->casual_labour->status =3;
            $invoice->order->casual_labour->save(); */

            foreach ($invoice->order->casual_labour as $casualLabour) {
                $casualLabour->status = 2;
                $casualLabour->save();
            }


            $invoice->invoice_status = 3;
            $invoice->payment_status = 3;

            $invoice->save();

        });

        return redirect()->route('invoices.index')->with('success', 'Invoice Cancelled!');
    }



    public function controlBackBtn(){
        return redirect()->route('invoices.index');
    }

    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



}
