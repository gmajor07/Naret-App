@extends('layouts.master')

@section('content')
<br>
    <div class="container-fluid">

            <div class="card card-default ">
                <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cart-plus"></i>Product Order Details.</h3>
                            <small class="float-right">
                                <a href="{{route('order_control_back_button')}}">
                                     <button type="button" class="btn btn-success btn-sm"><i class="fas fa-arrow-left " style="color:white;"></i> Back</button>
                                </a>
                            </small>
                </div>
                <div class="card-body">

                    <br>
                    @php
                    $i=0;
                    @endphp
                     <Address>
                         <b>Customer Name:</b> {{$prod_order->customer->name}}
                         <br>
                         <b>Customer Phone:</b> {{$prod_order->customer->phone}}
                         <br>
                         <b>Customer Address:</b> {{$prod_order->customer->location}}
                         <br>

                     </Address>



                    <!-- First Table row -->
                    <div class="row">
                      <div class="col-12 table">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Product</th>
                              <th>Description</th>
                              <th>Unity Price</th>
                              <th>Quantity</th>
                              <th>Amount</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($prod_order->products as $product)
                                  <tr>
                                    <td> {{$product->name }} </td>
                                    <td> {{$product->description}} @if($prod_order->description) {{$prod_order->description}} @endif </td>
                                    <td> {{number_format(($product->unity_price),'2','.',',')}} </td>
                                    <td> {{$product->order_products->quantity}}</td>
                                    <td> {{number_format(($product->unity_price * $product->order_products->quantity),'2','.',',')}} </td>
                                  </tr>
                            @endforeach

                            <tr style="color: #ff8c00;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>DISCOUNT</b></td>
                                <td><b>{{ $prod_order->invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($prod_order->invoice->discount),2,'.',',')}} </b></td>
                              </tr>
                            <tr style="color: #0069d9;">
                              <td></td>
                              <td></td>
                              <td></td>
                              <td><b>VAT (18%)</b></td>
                              <td><b>{{ $prod_order->invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($prod_order->invoice->vat),2,'.',',')}} </b></td>
                            </tr>
                            <tr style="color: #dc3545;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>WIHHOLDING TAX (5%)</b></td>
                                <td><b>{{ $prod_order->invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($prod_order->invoice->withholding_tax),2,'.',',')}} </b></td>
                              </tr>

                             @if ($prod_order->invoice->total_vat_inclusive > 0)
                             <tr style="color: #0f5bd8; font-weight: 700;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>TOTAL</b></td>
                                <td><b>{{ $prod_order->invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($prod_order->invoice->total_vat_inclusive),2,'.',',')}}</b></td>
                              </tr>
                              @else
                              <tr style="color: #0f5bd8; font-weight: 700;" style="color: #0f5bd8; font-weight: 700;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>TOTAL</b></td>
                                <td><b>{{ $prod_order->invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($prod_order->invoice->total_vat_exclusive),2,'.',',')}}</b></td>
                              </tr>

                             @endif

                           @if ($prod_order->invoice->payment_status == 1)
                            <tr>
                              <td></td>
                              <td ><b>PAID AMOUNT</b></td>
                              <td style="color: #0af144;"><b>{{ $prod_order->invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($prod_order->invoice->amount_paid),2,'.',',')}}</b></td>
                              <td></td>
                              <td></td>
                            </tr>
                            <tr>
                              <td></td>
                              <td><b>UNPAID AMOUNT</b></td>
                              <td style="color: #aa0404;"><b>{{ $prod_order->invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($prod_order->invoice->amount_due),2,'.',',')}}</b></td>
                              <td></td>
                              <td></td>
                            </tr>
                            @endif
                          </tbody>
                        </table>
                      </div>
                    </div>

                </div>
            </div>

    </div>



@endsection

@section('pagescripts')
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

<script type="text/javascript">

$(function() {

    $('.select2').select2()
                setTimeout(function() {
                    $("#success_element").hide();
        },
    2000);
});


</script>
@endsection
