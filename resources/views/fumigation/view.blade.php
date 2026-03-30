@extends('layouts.master')

@section('content')
<br>
    <div class="container-fluid">

            <div class="card card-default ">
                <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cart-plus"></i>   Fumigation Order {{$order->order_number}} Details.</h3>
                            <small class="float-right">
                                <a href="{{route('fumigation_control_back_button')}}">
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
                        <b>Customer Name:</b> {{$order->customer->name}}
                        <br>
                        <b>Customer Phone:</b> {{$order->customer->phone}}
                        <br>
                        <b>Customer Address:</b> {{$order->customer->location}}
                        <br>
                        <b>Customer PO Number:</b> {{ $order->po_number ?? 'N/A' }}
                        <br>
                    </Address>

                    <!-- First Table row -->
                    <div class="row">
                      <div class="col-12 table">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Fumigation Number</th>
                              <th>Description</th>
                              <th>Quantity</th>
                              <th>Unit Price</th>
                              <th>Amount</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($order->fumigations as $fumigation)
                                  <tr>
                                    <td> {{$fumigation->fumigation_number }} </td>
                                    <td> {{$fumigation->description}} </td>
                                    <td> {{$fumigation->item_quantity}} </td>
                                    <td>{{number_format(($fumigation->unit_price),2,'.',',')}}</td>
                                    <td> {{number_format(($fumigation->unit_price * $fumigation->item_quantity),'2','.',',')}} </td>
                                  </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>DISCOUNT</b></td>
                                <td><b>{{ $order->invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($order->invoice->discount),2,'.',',')}} </b></td>
                              </tr>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td><b>VAT (18%)</b></td>
                              <td><b>{{ $order->invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($order->invoice->vat),2,'.',',')}} </b></td>
                            </tr>

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>WITHHOLDING TAX (5%)</b></td>
                                <td><b>{{ $order->invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($order->invoice->withholding_tax),2,'.',',')}} </b></td>
                              </tr>

                             @if ($order->invoice->total_vat_inclusive > 0)
                             <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>TOTAL</b></td>
                                <td><b>{{ $order->invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($order->invoice->total_vat_inclusive),2,'.',',')}}</b></td>
                              </tr>
                              @else
                              <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>TOTAL</b></td>
                                <td><b>{{ $order->invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($order->invoice->total_vat_exclusive),2,'.',',')}}</b></td>
                              </tr>

                             @endif

                           @if ($order->invoice->payment_status == 1)
                            <tr>
                              <td></td>
                              <td ><b>PAID AMOUNT</b></td>
                              <td style="color: #0af144;"><b>{{ $order->invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($order->invoice->amount_paid),2,'.',',')}}</b></td>
                              <td></td>
                              <td></td>
                            </tr>
                            <tr>
                              <td></td>
                              <td><b>UNPAID AMOUNT</b></td>
                              <td style="color: #aa0404;"><b>{{ $order->invoice->currency_id == 2 ? 'USD $' : 'TZs.' }}{{number_format(($order->invoice->amount_due),2,'.',',')}}</b></td>
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
