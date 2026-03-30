@extends('layouts.master')

@section('content')
  <br>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- Main content -->
        <div class="invoice p-3 mb-3">
          <!-- title row -->
          <div class="row">
            <div class="col-12">
              <h4>
                <i class="fas fa-money-bill-alt"></i> Bill Informations.

                <small class="float-right">

                    <a href="{{route('control_back_button')}}" style="margin-right:-100px;">
                        <button type="button" class="btn btn-success btn-sm"><i class="fas fa-arrow-left " style="color:white;"></i> Back</button>
                    </a>
                    <br>
                 Due Date: {{Carbon\Carbon::parse($invoice->created_date)->format('d-m-Y')}}
                </small>
              </h4>
            </div>
            <!-- /.col -->
          </div>
          <br>
          <!-- info row -->
          <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
             Bill to
              <address>
                <strong>
                    {{$invoice->customer->name}}
                </strong><br>
                <b>Invoice Number:</b> {{$invoice->invoice_number}}<br>
                <b>TIN Number:</b> {{$invoice->customer->tin_number}}<br>
                <b>VRN Number:</b> {{$invoice->customer->vrn}}<br>
                <b>Address:</b> {{$invoice->customer->location}}<br>
                <b>Phone:</b> {{$invoice->customer->phone}}<br>
                <b>Email:</b> {{$invoice->customer->email}}
              </address>
            </div>
            <!-- /.col -->
          </div>

          <br>

          <br>
<br>
          @php
          $i=0;
          @endphp

          <!-- First Table row -->
          <div class="row">
            <div class="col-12 table">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>S/N</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($invoice->order->fumigations as $key =>  $fumigation)
                        <tr>
                            <td> {{ ++$key }} </td>
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
                    <td><b>{{ $invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} -{{number_format(($invoice->discount),2,'.',',')}} </b></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>VAT 18%</b></td>
                    <td><b>{{ $invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($invoice->vat),2,'.',',')}} </b></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>TOTAL AMOUNT</b></td>
                    <td><b>{{ $invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($invoice->amount_due + $invoice->discount),2,'.',',')}} </b></td>
                  </tr>
                 
                    @if ($invoice->total_vat_inclusive > 0)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b>TOTAL PAYABLE AMOUNT</b></td>
                            <td><b>{{ $invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($invoice->total_vat_inclusive),2,'.',',')}}</b></td>
                        </tr>
                    @elseif ($invoice->total_vat_inclusive == 0)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b>TOTAL PAYABLE AMOUNT</b></td>
                            <td><b>{{ $invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($invoice->total_vat_exclusive),2,'.',',')}}</b></td>
                        </tr>

                    @endif

                 @if ($invoice->payment_status == 1)
                  <tr>
                    <td></td>
                    <td ><b>PAID AMOUNT</b></td>
                    <td style="color: #0af144;"><b>{{ $invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($invoice->amount_paid),2,'.',',')}}</b></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><b>UNPAID AMOUNT</b></td>
                    <td style="color: #aa0404;"><b>{{ $invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{number_format(($invoice->amount_due),2,'.',',')}}</b></td>
                    <td></td>
                    <td></td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>

          <form role="form" method="post" action="{{route('savePayment',$invoice->id)}}" id="saveControl">
            @csrf
            @method('POST')
            <div class="form-group">
              <label for="exampleInputfirstName">Amount Payed</label>
              <input type="text" name="payment" class="form-control" id="payment" required >
            </div>
            <a href="{{ route('invoices.index')}}">
              <button type="button" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
            </a>
            <button type="submit" class="btn btn-success" style="float:right;"><b>Add Payment</b></button>
          </form>
          <br>



        </div>
        <!-- /.invoice -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->

@endsection

@section('pagescripts')

@endsection
