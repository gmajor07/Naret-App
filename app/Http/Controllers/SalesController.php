<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::where('approved_by','!=',0)->orderBy('updated_at','desc')->get();
        return view('sales.index',compact('sales'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function approveView()
    {
        $unapproved = Sale::where('approved_by',0)->where('rejected',0)->get();

        return view('sales.approve',compact('unapproved'));
    }

      /**
     * approving sales.
     */


     public function approve($id)
     {
         $sale = Sale::find($id);
     
         if (!$sale) {
             return redirect()->route('unapprovedSales')->with('failure', 'Sale not found.');
         }
     
         $invoice = $sale->invoice;
         $order = $sale->order;
     
         if (!$invoice || !$order) {
             return redirect()->route('unapprovedSales')->with('failure', 'Associated invoice or order not found.');
         }
     
         // Prevent overpayment
         if ($sale->total_amount > $invoice->amount_due) {
             return redirect()->route('unapprovedSales')->with('failure', 'Payment exceeds invoice due amount.');
         }
     
         // ✅ Reduce stock only once and only for products with type_id == 1
         if ($invoice->stock_already_reduced == 0) {
             // Validate stock availability
             foreach ($order->products as $product) {
                 if ($product->type_id == 1) {
                     $qty = $product->order_products->quantity;
                     if ($qty > $product->stock_quantity) {
                         return redirect()->route('unapprovedSales')->with('failure', 'Insufficient stock for ' . $product->name);
                     }
                 }
             }
     
             // Deduct stock
             foreach ($order->products as $product) {
                 if ($product->type_id == 1) {
                     $qty = $product->order_products->quantity;
                     $product->stock_quantity -= $qty;
                     $product->save();
                 }
             }
     
             $invoice->stock_already_reduced = 1;
         }
     
         // Update invoice payment info
         $invoice->amount_paid += $sale->total_amount;
         $invoice->amount_due -= $sale->total_amount;
     
         if ($invoice->amount_due <= 0) {
             $invoice->payment_status = 2; // full paid
             $invoice->invoice_status = 2;
             $order->status = 2;
         } else {
             $invoice->payment_status = 1; // partial
             $invoice->invoice_status = 1;
             $order->status = 1;
         }
     
         $sale->approved_by = Auth::id();
         $sale->save();
     
         $invoice->save();
         $order->save();
     
         return redirect()->route('unapprovedSales')->with('success', 'Sale approved.');
     }
     

    //Reject sales
    public function rejectSales(Request $request, string $id)
    {
        $validatedData= $request->validate([
            'comment' => 'required|string|max:255',

        ]);

        DB::transaction( function() use($request, $id){

            $reject = Sale::find($id);

            $reject->comment = $request['comment'];
            $reject->rejected = 1;

            /* $order = $reject->order;
            $order->invoice->invoice_status = 1;
            $order->invoice->save();

            foreach ($order->casual_labour as $casualLabour) {
                $casualLabour->status = 1;
                $casualLabour->save();
            }

            $order->status=1;
            $order->save(); */

            $reject->save();


        });


        return redirect()->route('unapprovedSales')->with('success', 'Returned to seller!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function allRejectedSales()
    {
        $rejected = Sale::where('rejected',1)->where('approved_by',0)->get();

        return view('sales.rejectedSales',compact('rejected'));
    }

    /**
     * Display the specified resource.
     */
    public function singleRejectedView(string $id)
    {
        $sale = Sale::findOrFail($id);

        return view('sales.view',compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }


    public function updateSales(Request $request, string $id)
    {
        $validatedData= $request->validate([
            'amount' => 'nullable|numeric|min:0',
            'comment' => 'required|string|max:255',

        ]);

        $sale = Sale::findOrFail($id);

        DB::transaction(function () use($validatedData, $request, $sale) {


           $sale->total_amount = $request['amount'];
           $sale->comment = $request['comment'];
           $sale->rejected =0;

           $sale->save();

        });


        return redirect()->route('allRejectedSales')->with('success','Sales record updated');
    }

    public function controlBackBtn(){
        return redirect()->route('allRejectedSales');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
