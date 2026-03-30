<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        table {
            width: 100%;
            border-spacing: 0;
        }
        th {
            border-top: 1px solid #ddd;
            padding-top: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
            text-align: center;
        }
        td {

            padding-top: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
            text-align: center;
        }

    </style>
</head>
<body>

    <div class="container">
        <h2 class="justify-content-center">Naret Compay Report</h2>

         <br><br>

         <table  id="example1" class="table table-bordered table-striped content">
            <thead>
                    <tr style="background-color: #1b5e85; color:white;">
                            <th>S/N</th>
                            <th>Invoice Number</th>
                            <th>Customer</th>
                            <th>Order Number</th>
                            <th>Amount</th>
                            <th>VAT</th>
                            <th>Discount</th>
                            <th>Total VAT Inclusive</th>
                            <th>Total VAT Exclusive</th>
                            <th>Payable Amount</th>
                            <th>Amount Paid</th>
                            <th>Amount Due</th>
                            <th>Payment Status</th>
                            <th>Currency</th>
                            <th>Invoice Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $key => $invoice)
                            <tr>
                                <td> {{ ++$key }} </td>
                                <td> {{ $invoice->invoice_number }} </td>
                                <td> {{ $invoice->customer->name }} </td>
                                <td> {{ $invoice->order->order_number}} </td>
                                <td> {{ number_format(( $invoice->total_amount),2,'.',',')}} </td>
                                <td> {{ number_format(( $invoice->vat),2,'.',',') }} </td>
                                <td> {{ number_format(( $invoice->discount),2,'.',',')}} </td>
                                <td> {{ number_format(( $invoice->total_vat_inclusive),2,'.',',')}} </td>
                                <td> {{ number_format(( $invoice->total_vat_exclusive),2,'.',',')}} </td>
                                <td> {{ number_format(( $invoice->payable_amount),2,'.',',') }} </td>
                                <td> {{ number_format(( $invoice->amount_paid),2,'.',',') }} </td>
                                <td> {{ number_format(( $invoice->amount_due),2,'.',',')}} </td>
                                <td>
                                @if ($invoice->payment_status == 0)
                                   Pending
                                @elseif ($invoice->payment_status == 1)
                                    Partial Paid
                                @elseif ($invoice->payment_status == 2)
                                   Paid
                                @else
                                   Cancelled
                                @endif
                                </td>
                                <td> {{ $invoice->currency->name }} </td>
                                <td> @if($invoice->invoice_type == 1)
                                          ORD
                                     @elseif($invoice->invoice_type == 2)
                                          FUM
                                     @elseif($invoice->invoice_type == 3)
                                          CAS
                                     @endif
                                  </td>
                                {{-- change invoice type to type id --}}
                               {{--  number_format(( $invoice->payment_status),2,'.',',') --}}
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @php
                                $firstInvoice = $invoices->first();
                            @endphp

                            @if($firstInvoice)
                            <!-- <td>@if($firstInvoice->payment_status == 0)
                                <b>TOTAL</b>
                                @endif
                            </td> -->
                            <td>
                                @if($firstInvoice->payment_status == 0)
                                <b>TOTAL</b>
                                
                                @elseif($firstInvoice->payment_status == 1 || $firstInvoice->payment_status == 2)
                                  <b>UNPAID</b>
                                 @endif
                            </td>
                            <td>
                                @if($firstInvoice->payment_status == 0)
                                <b>{{number_format(($totalAmount),2,'.',',')}}</b>
                                @elseif($firstInvoice->payment_status == 1 || $firstInvoice->payment_status == 2)
                                  <b>{{number_format(($remainingDebt),2,'.',',')}}</b>
                                 @endif
                            </td>
                            @endif
                            <td></td>
                            <td></td>
                            <td></td>
                            @if(!$firstInvoice)
                            <td></td>
                            <td></td>
                            @endif
                        </tr>
                    </tbody>
         </table>
    </div>

</body>
</html>
