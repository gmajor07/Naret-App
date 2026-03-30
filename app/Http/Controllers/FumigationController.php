<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Type;
use App\Models\User;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Fumigation;
use App\Models\Consumption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rules\DiscountAmountLessThanTotal;


class FumigationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /* $fumigations = Fumigation::all(); */
        //$orders = Order::where('type_id', 2)->get();
        $orders = Order::where('type_id', 2)->orderBy('status', 'asc')->get();
        $customers = Customer::all();
        $currencies = Currency::all();
        return view('fumigation.index',compact('customers','orders','currencies' ));
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
            'item_quantity.*' => 'required|integer|min:1',
            'unit_price.*' => 'required|integer|min:0',
            'po_number' => 'nullable|string|max:255',
            'apply_vat' => 'nullable|boolean',
            'withholding' => 'nullable|boolean',
            'discount_amount' => 'nullable|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'date' => 'required|date',

            //'fumigationsArr' => 'required|array'
        ]);

        DB::transaction(function() use($validatedData, $request){

            $type = Type::where('name','=','fumigation')->value('id');
            /* $fumigationsData = $request->input('fumigationsArr'); */

            $fumigations = [];
                foreach ($validatedData['description'] as $key => $description) {
                    $fumigation = new Fumigation();
                    $fumigation->description = $description;
                    $fumigation->item_quantity =  $validatedData['item_quantity'][$key];
                    $fumigation->unit_price = $validatedData['unit_price'][$key];
                    $fumigation->fumigation_date = Carbon::parse($validatedData['date']);
                    $fumigation->status = 0;
                    // Set other product attributes as needed
                    $fumigation->save();

                    $fumigations[] = $fumigation;
                }

            $order = new Order();
            $order->customer_id = $request['customer_id'];
            $order->type_id = $type;
            $order->order_date = Carbon::parse($validatedData['date']);
            $order->created_at = Carbon::parse($validatedData['date']);
            $order->status = 0;
            $order->po_number = $request->input('po_number');
            $order->save();

            foreach ($fumigations as $fumigation) {
               //$quantity = $productData['quantity']; // Assuming quantity is submitted with product data
                $order->fumigations()->attach($fumigation->id, ['created_at' =>Carbon::parse($validatedData['date']), 'updated_at' => now()]);
            }


            $totalAmount = 0;
            foreach ($validatedData['item_quantity'] as $key => $quantity) {
                $totalAmount += $validatedData['unit_price'][$key] * $quantity;
            }

            $invoice = new Invoice();
            $invoice->customer_id = $order->customer_id;
            $invoice->order_id = $order->id;

            if($request->input('discount_amount')){
                $invoice->discount = $request['discount_amount'];
                if( $request->input('apply_vat') ==1 ){
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
                if( $request->input('apply_vat') ==1 ){
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

            if($request->withholding == 1){
                $invoice->withholding_tax = $invoice->payable_amount * 0.05;
            }
            else{
               $invoice->withholding_tax = 0;
            }
            $invoice->amount_paid=0;
            $invoice->payment_status =0;
            $invoice->invoice_status =0;
            $invoice->currency_id = $request['currency_id'];
            $invoice->invoice_type = 2;
            $invoice->due_date=now()->addDays(7);
            $invoice->created_at = Carbon::parse($validatedData['date']);
            $invoice->save();


        });

        return redirect()->route('fumigation.index')->with('success', 'Order placed successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::findOrFail($id);

        return view('fumigation.view',compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::findOrFail($id);

        return view('fumigation.edit',compact('order'));
    }

    public function fumigationControlBackButton(){
        return redirect()->route('fumigation.index');
    }

    public function consumptionfumigationControlBackButton(){
        return redirect()->route('assignView');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'fumigation_id.*' => 'nullable|exists:fumigations,id',
            'description.*' => 'required|string|max:255',
            'item_quantity.*' => 'required|integer|min:1',
            'unit_price.*' => 'required|integer|min:0',
            'po_number' => 'nullable|string|max:255',
            'date' => 'required|date',
            'apply_vat' => 'nullable|boolean',
            'withholding' => 'nullable|boolean',
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
        ]);


        $order = Order::findOrFail($id);

        DB::transaction(function () use ($validatedData, $request, $order) {
            $selectedDate = Carbon::parse($validatedData['date']);

            // Retrieve existing fumigations associated with the order
            $existingFumigations = $order->fumigations;

            // Detach all current fumigations from the order
            $order->fumigations()->detach();

            $order->customer_id = $request['customer_id'];
            $order->order_date = $selectedDate;
            $order->created_at = $selectedDate;
            $order->po_number = $request->input('po_number');
            $order->save();

            foreach ($validatedData['description'] as $key => $description) {
                // Check if fumigation_id exists and is valid
                $fumigationId = $validatedData['fumigation_id'][$key] ?? null;

                if ($fumigationId && Fumigation::find($fumigationId)) {
                    // If fumigation_id exists and is valid, update the existing fumigation
                    $fumigation = Fumigation::find($fumigationId);
                    $fumigation->description = $description;
                    $fumigation->item_quantity = $validatedData['item_quantity'][$key];
                    $fumigation->unit_price = $validatedData['unit_price'][$key];
                    $fumigation->fumigation_date = $selectedDate;
                    $fumigation->save();
                } else {
                    // If fumigation_id is not provided or invalid, create a new fumigation
                    $fumigation = new Fumigation();
                    $fumigation->description = $description;
                    $fumigation->item_quantity = $validatedData['item_quantity'][$key];
                    $fumigation->unit_price = $validatedData['unit_price'][$key];
                    $fumigation->fumigation_date = $selectedDate;
                    $fumigation->status = 0;
                    $fumigation->save();
                }

                // Attach the fumigation to the order
                $order->fumigations()->attach($fumigation->id, ['created_at' => $selectedDate, 'updated_at' => now()]);
            }

            // Delete the previously associated fumigations that are no longer associated
            foreach ($existingFumigations as $fumigation) {
                //if (!in_array($fumigation->id, $validatedData['fumigation_id'])) {
                    $fumigation->delete();
                //}
            }

            // Update order details
            $totalAmount = 0;
            foreach ($validatedData['item_quantity'] as $key => $quantity) {
                $totalAmount += $validatedData['unit_price'][$key] * $quantity;
            }
            $invoice = $order->invoice;
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
            $invoice->customer_id = $order->customer_id;
            $invoice->created_at = $selectedDate;
            $invoice->due_date = $selectedDate->copy()->addDays(7);
            $invoice->save();
        });

        return redirect()->route('fumigation.index')->with('success', 'Order updated successfully!');
    }



    /* showing fumigation to assign fumigator  */
    public function assignView(){

        //$consumptions =Consumption::all();
        $consumptions = Consumption::orderBy('status', 'asc')->get();
        $users = User::where('role_id','=',3)->get();
        $fumigations = Fumigation::all();
        $products = Product::where('type_id','=',2)->get();

        return view('fumigation.assign',compact('consumptions','users','fumigations','products'));

    }


    public function assignStore(Request $request){
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'item_quantity' => 'required|integer|min:1',
            'container_quantity' => 'required|integer|min:1',
            'fumigation_id' => 'required|exists:fumigations,id',
            'product_id'  => 'required|exists:users,id',

        ]);

        $fumigation_id = $request['fumigation_id'];
        $fumigator_id = $request['user_id'];
        $container = $request['container_quantity'];


        $consumption  = new Consumption();

        $consumption->product_id = $request['product_id'];
        $consumption->item_quantity = $request['item_quantity'];
        $consumption->status = 0;
        $consumption->save();

        $consumption->user()->attach($fumigator_id,['fumigation_id' =>$fumigation_id,
                                   'container_quantity' => $container,]);



        return redirect()->route('assignView')->with('success', 'Assigned successfully!');

    }


    public function assignUpdate (Request $request, string $id){

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'container_quantity' => 'required|integer|min:1',
            'fumigation_id' => 'required|exists:fumigations,id',

        ]);

        $fumigator_id = $request['user_id'];
        $fumigation_id = $request['fumigation_id'];
        $container = $request['container_quantity'];
        $consumption = Consumption::findOrFail($id);

        $consumption->user()->attach($fumigator_id,['fumigation_id' =>$fumigation_id,
        'container_quantity' => $container,]);


        return redirect()->route('assignView')->with('success', 'Updated successfully!');
    }

    public function assignedConsumptionShow (string $id){

       $consumption =  Consumption::findOrFail($id);


       return view ('fumigation.consumption.show',compact('consumption'));
    }



    public function recordFinishedFumigationProduct($id){
        
        $consumption = Consumption::find($id);

        DB::transaction(function() use($consumption){
            $consumption->status=1;
            $consumption->save();
    
    
            $product = $consumption->product;
            $product->stock_quantity -= $consumption->item_quantity;
            $product->save();

        });
        
        return redirect()->route('assignView')->with('success', 'Updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fumigation_order = Order::with('invoice')->findOrFail($id);

        if ($fumigation_order->invoice) {

            $fumigation_order->invoice->invoice_status = 3;
            $fumigation_order->invoice->payment_status = 3;
            $fumigation_order->invoice->save();
            //$fumigation_order->invoice->delete();
        }
       // $fumigation_order->fumigations()->wherePivot('deleted_at', null)->updateExistingPivot($id, ['deleted_at' => now()]);
        //$fumigation_order->fumigations()->wherePivot('order_id', $id)->updateExistingPivot(['deleted_at' => now()]);
        $fumigation_order->status = 3;
        $fumigation_order->save();
        //$fumigation_order->delete();



        return redirect()->route('fumigation.index')->with('success', 'Fumigation order deleted successfully!');
    }

    public function fumigationCancell($id){

        $order = Order::findOrfail($id);

        DB::transaction(function() use($order){
            $order->status = 3;

           foreach($order->fumigations as $fumigation){
                $fumigation->status = 3;
                $fumigation->save();
           }


           if( $order->invoice){
            $order->invoice->invoice_status = 3;
            $order->invoice->save();
           }

           $order->save();

        });

        return redirect()->route('fumigation.index')->with('success', 'Order Cancelled!');

    }

    public function controlBackBtn(){
        return redirect()->route('assignView');
    }

}
