<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Carbon\CarbonImmutable;
use phpDocumentor\Reflection\Types\This;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$orders = Order::where('type_id', 1)->get();
        $orders = Order::where('type_id', 1)->orderBy('status', 'asc')->get();
        $customers = Customer::all();
        $products = Product::all();
        $currencies = Currency::all();
        return view('orders.index',compact('orders','customers','products','currencies'));
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
        $validatedData =  $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
            'po_number' => 'nullable|string|max:255',
            'apply_vat' => 'nullable|boolean',
            'withholding2' => 'nullable|boolean',
            'discount_amount' => 'nullable|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);
        DB::transaction(function () use ($request, $validatedData) {
            $order = new Order();
            $order->customer_id = $validatedData['customer_id'];
            $order->order_date = Carbon::parse($validatedData['date']);
            $order->created_at = Carbon::parse($validatedData['date']);
            $order->type_id = 1;
            $order->status = 0;
            $order->po_number = $request->input('po_number');
            $order->description = $request->input('description');
            $order->save();

            // Attach products to the order with quantities
            foreach ($request->input('product_ids') as $key => $product_id){
                $product = Product::find($product_id);
                $quantity = $request->input('quantities')[$key];
                $order->products()->attach($product, ['quantity' => $quantity,'created_at' =>Carbon::parse($validatedData['date']),
                'updated_at' =>now() ]);



            }


            $totalAmount = $order->products->sum(function ($product) {
            return optional($product->order_products)->quantity * $product->unity_price;

            });

            $invoice = new Invoice();
            $invoice->customer_id = $order->customer_id;
            //$invoice->invoice_number = 9;
            $invoice->order_id = $order->id;

            if($request->input('discount_amount')){
                $invoice->discount = $request['discount_amount'];
                if( $request->input('apply_vat') ==1 ){
                    $invoice->total_vat_inclusive = ($totalAmount-$invoice->discount) + (($totalAmount-$invoice->discount) * 0.18);
                    $invoice->vat = ($totalAmount-$invoice->discount) * 0.18;
                    $invoice->amount_due=$invoice->total_vat_inclusive;
                    $invoice->total_vat_exclusive = 0;
                }else{
                    $invoice->total_vat_inclusive = 0;
                    $invoice->vat = 0;
                    $invoice->amount_due=$totalAmount-$invoice->discount;
                    $invoice->total_vat_exclusive = $totalAmount-$invoice->discount;
                }
            }
            else{
                $invoice->discount = 0;
                if( $request->input('apply_vat') ==1 ){
                    $invoice->total_vat_inclusive = $totalAmount + ($totalAmount * 0.18);
                    $invoice->vat = $totalAmount * 0.18;
                    $invoice->amount_due=$invoice->total_vat_inclusive;
                    $invoice->total_vat_exclusive = 0;
                }else{
                    $invoice->total_vat_inclusive = 0;
                    $invoice->vat = 0;
                    $invoice->amount_due=$totalAmount;
                    $invoice->total_vat_exclusive = $totalAmount;
                }

            }

        $invoice->payable_amount = ($totalAmount + $invoice->vat) - $request['discount_amount'];

          if($request->withholding2 == 1){
                $invoice->withholding_tax = $invoice->payable_amount * 0.05;
          }
          else{
            $invoice->withholding_tax = 0;
          }

          $invoice->total_amount =  $totalAmount;
          $invoice->amount_paid=0;
          $invoice->payment_status =0;
          $invoice->invoice_status =0;
          $invoice->currency_id= $request['currency_id'];
          $invoice->invoice_type = 1;
          $invoice->due_date=now()->addDays(7);
          $invoice->created_at = Carbon::parse($validatedData['date']);
          $invoice->save();
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prod_order = Order::findOrFail($id);

        return view('orders.view', compact('prod_order'));
    }

    public function order_control_back_button(){
        return redirect()->route('orders.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        $customers = Customer::all();
        $products = Product::all();
        $currencies = Currency::all();

        return view('orders.edit',compact('order','customers','products','currencies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
            'po_number' => 'nullable|string|max:255',
            'date' => 'required|date',
            'apply_vat' => 'nullable|boolean',
            'withholding' => 'nullable|boolean',
            'discount_amount' => 'nullable|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'description' => 'nullable|string'
        ]);
        $order = Order::findOrFail($id);

        DB::transaction(function () use($request,$id,$order,$validatedData){

             $order->products()->detach();

            $selectedDate = Carbon::parse($validatedData['date']);

            $order->customer_id = $request['customer_id'];
            $order->order_date = $selectedDate;
            $order->created_at = $selectedDate;
            $order->type_id = 1;
            $order->status = 0;
            $order->po_number = $request->input('po_number');
            $order->description = $request->input('description');
            $order->save();


              // Attach products to the order with quantities
                foreach ($request->input('product_ids') as $key => $product_id){
                    $product = Product::find($product_id);
                    $quantity = $request->input('quantities')[$key];
                    $order->products()->attach($product, ['quantity' => $quantity,'created_at' => $selectedDate ,
                    'updated_at' => now() ]);

                }


                $totalAmount = $order->products->sum(function ($product) {
                    return optional($product->order_products)->quantity * $product->unity_price;

                });

                $invoice = $order->invoice;
                $invoice->customer_id = $order->customer_id;
                //$invoice->invoice_number = 9;
                $invoice->order_id = $order->id;

                if($request->input('discount_amount')){
                    $invoice->discount = $request['discount_amount'];
                    if( $request->input('apply_vat') ==1 ){
                        $invoice->total_vat_inclusive = ($totalAmount-$invoice->discount) + (($totalAmount-$invoice->discount) * 0.18);
                        $invoice->vat = ($totalAmount-$invoice->discount) * 0.18;
                        $invoice->amount_due=$invoice->total_vat_inclusive;
                        $invoice->total_vat_exclusive = 0;
                    }else{
                        $invoice->total_vat_inclusive = 0;
                        $invoice->vat = 0;
                        $invoice->amount_due=$totalAmount-$invoice->discount;
                        $invoice->total_vat_exclusive = $totalAmount-$invoice->discount;
                    }
                }
                else{
                    $invoice->discount = 0;
                    if( $request->input('apply_vat') ==1 ){
                        $invoice->total_vat_inclusive = $totalAmount + ($totalAmount * 0.18);
                        $invoice->vat = $totalAmount * 0.18;
                        $invoice->amount_due=$invoice->total_vat_inclusive;
                        $invoice->total_vat_exclusive = 0;
                    }else{
                        $invoice->total_vat_inclusive = 0;
                        $invoice->vat = 0;
                        $invoice->amount_due=$totalAmount;
                        $invoice->total_vat_exclusive = $totalAmount;
                    }

                }

            $invoice->payable_amount = ($totalAmount + $invoice->vat) - $request['discount_amount'];

              if($request->withholding == 1){
                    $invoice->withholding_tax = $invoice->payable_amount * 0.05;
              }
              else{
                $invoice->withholding_tax = 0;
              }
              $invoice->total_amount =  $totalAmount;

              $invoice->amount_paid=0;
              $invoice->payment_status =0;
              $invoice->invoice_status =0;
              $invoice->currency_id= $request['currency_id'];
              $invoice->invoice_type = 1;
              $invoice->due_date = $selectedDate->copy()->addDays(7);
              $invoice->created_at = $selectedDate;
              $invoice->save();


        });

        return redirect()->route('orders.index')->with('success', 'Order Updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::transaction( function () use($id){
            $prod_order = Order::findOrFail($id);

            //$order = $casual->orders->first();

            $prod_order->products()->detach();


            $prod_order->invoice->delete();
            $prod_order->delete();
            //$casual->delete();
        });

         return redirect()->route('orders.index')->with('success', 'Order deleted');
    }


    public function printNaretInvoice ($order_id){

      $todays_date = Carbon::now('Africa/Dar_es_Salaam');
      $order = Order::find($order_id);
      $invoice = $order->invoice;
      $company = \App\Models\Company::with('accounts.currency')->firstOrFail();
      $status = 'invoice'; // or 'profoma' based on something

      $pdf = PDF::loadView('invoicePrint.printNaretCompanyInvoice',compact('invoice','company','status'));
      $pdf->setPaper('A4','portrait');
      return $pdf->download($order->customer->name.'_Invoice'.'pdf');

    }

    public function printNaretFummifationInvoice ($order_id){

        $order = Order::find($order_id);
        $invoice = $order->invoice;
        $company = \App\Models\Company::with('accounts.currency')->orderBy('id', 'desc')->firstOrFail();
        $status = 'invoice';

        $pdf = PDF::loadView('invoicePrint.printNaretFumigationInvoice',compact('invoice','company','status'));
        $pdf->setPaper('A4','portrait');

        return $pdf->download($order->customer->name.'_Invoice'.'pdf');

    }
}
