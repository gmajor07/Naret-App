<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            position: relative;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 12px;
        }

        .text-watermark {
            position: fixed;
            top: 35%;
           /*  left: -25%;  *//* Shift more to the left */
            font-size: 60px; /* Slightly smaller to ensure full visibility */
            color: rgba(0, 0, 0, 0.05);
            transform: rotate(-45deg);
            width: 100%; /* Extend width to give it room */
            text-align: center;
            z-index: -1;
            pointer-events: none;
            font-weight: bold;
            white-space: nowrap;
        }


        table {
            width: 100%;
            border-spacing: 0;
        }

        th, td {
            padding-top: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            border-top: 1px solid #ddd;
        }

        .footer {
            position: relative;
            bottom: 0;
            left: 0;
            width: 100%;
            display: block;
        }

        @page {
            margin: 30px;
        }
    </style>
</head>

<body>
    <!-- ✅ Text Watermark -->
    <div class="text-watermark">
        @if($invoice->vat > 0)
            NARET COMPANY <br>
            LIMITED
        @else
            NARET FUMIGATION AND<br>
            GENERAL CLEANNESS
        @endif
    </div>

    <!-- ✅ Invoice Content -->
    <table>
        <tr>
            @if ($status == 'invoice')
                <td style="text-align: left; width:60%;">
                    <h1 style="margin-left:30px;">INVOICE</h1>
                </td>
            @else
                <td style="text-align: left; width:60%;">
                    <h1 style="margin-left:30px;">PROFOMA INVOICE</h1>
                </td>
            @endif

            <td>
                @if($invoice->vat > 0)
                    <img src="https://naret.co.tz/naret-app/assets/img/naret_company.jpg" width="60%" height="130px">
                @else
                    <img src="https://naret.co.tz/naret-app/assets/img/naret.jpg" width="60%" height="130px">
                @endif
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="text-align: left; width:40%;">
                <div style="margin-top:-50px;">
                    <b>{{ $invoice->customer->name }}</b><br>
                    <div style="color:rgb(70, 65, 65);">
                        Email: {{ $invoice->customer->email }}<br>
                        Phone Number: {{ $invoice->customer->phone }}<br>
                        Location: {{ $invoice->customer->location }}<br>
                        Tin Number: {{ $invoice->customer->tin_number }}<br>
                        VRN: {{ $invoice->customer->vrn }}<br>
                    </div>
                  </div>
            </td>

            <td cellspacing="20" style="width: 10%; text-align: left; border-right:1px solid black;">
                @if (!empty($invoice->order?->po_number))
                    <b>Customer PO Number:</b> {{ $invoice->order->po_number }}<br>
                @endif
                <b>Invoice Number:</b> {{ $invoice->invoice_number }}<br>
                <b>Invoice Date:</b> {{ $invoice->created_at->format('d/m/Y') }}<br>
                <b>Invoice Due Date:</b> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}<br>
                {{ \Carbon\Carbon::parse($invoice->due_date)->format('H:i:s') }}
            </td>

            @if ($company)
                 <td>
                    <div style="text-align: left; margin-left:30px; margin-top:-40px;">
                        @if($invoice->vat > 0)
                            <b>NARET COMPANY LIMITED</b><br>
                            <p style="margin-top: 0px; color:rgb(70, 65, 65);">
                                Opposite of Gate 5 stand, Shimo la udongo road, Kurasini,<br>
                                P.O.Box 6230, Dar es Salaam, Tanzania.<br>
                                Phone Number: 0753995084/0754689775<br>
                                Email: naret@naret.co.tz<br>
                                Tin Number: 155884452<br>
                                VRN: +40039930
                            </p>
                        @else
                            <b>NARET FUMIGATION AND GENERAL CLEANNESS</b><br>
                            <p style="margin-top: 0px; color:rgb(70, 65, 65);">
                                Opposite of Gate 5 stand, Shimo la udongo road, Kurasini,<br>
                                P.O.Box 6230, Dar es Salaam, Tanzania.<br>
                                Phone Number: 0753995084/0754689775<br>
                                Email: naret@naret.co.tz<br>
                                Tin Number: 155884452<br>
                                VRN: +40039930
                            </p>
                        @endif
                    </div>
                </td>
            @endif
        </tr>
    </table>

<br><br>
    <!-- 🧾 Invoice Items Table -->
   <table id="example1" class="table table-bordered table-striped content">
        <thead>
            @if($invoice->invoice_type == 1)
                <tr style="background-color: #1b5e85; color:white;">
                    <th>S/N</th>
                    <th>Product name</th>
                    <th>Product Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            @elseif ($invoice->invoice_type == 3)
                <tr style="background-color: #1b5e85; color:white;">
                    <th>S/N</th>
                    <th>Description</th>
                    <th>Labour Charges</th>
                    <th>Administration Fee</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                </tr>
            @else
                <tr style="background-color: #1b5e85; color:white;">
                    <th>S/N</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            @endif
        </thead>
        <tbody>
            @if($invoice->invoice_type == 1)
                @foreach ($invoice->order->products as $key => $product)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }} @if($invoice->order->description) {{ $invoice->order->description }} @endif</td>
                        <td>{{ $product->order_products->quantity }}</td>
                        <td>{{ number_format($product->unity_price, 2, '.', ',') }}</td>
                        <td>{{ number_format($product->unity_price * $product->order_products->quantity, 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            @elseif($invoice->invoice_type == 3)
                @foreach ($invoice->order->casual_labour as $key => $casual)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $casual->description }}</td>
                        <td>{{ number_format($casual->labour_charge, 2, '.', ',') }}</td>
                        <td>{{ number_format($casual->administration_fee, 2, '.', ',') }}</td>
                        <td>{{ $casual->quantity }}</td>
                        <td>{{ number_format(($casual->labour_charge + $casual->administration_fee) * $casual->quantity, 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            @else
                @foreach ($invoice->order->fumigations as $key => $fumigation)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>Fumigation</td>
                        <td>{{ $fumigation->description }}</td>
                        <td>{{ $fumigation->item_quantity }}</td>
                        <td>{{ number_format($fumigation->unit_price, 2, '.', ',') }}</td>
                        <td>{{ number_format($fumigation->unit_price * $fumigation->item_quantity, 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            @endif

            <!-- Totals -->
            <tr><td colspan="4"></td><td>VAT (18%)</td><td>{{ number_format($invoice->vat, 2, '.', ',') }}</td></tr>
            <tr><td colspan="4"></td><td>DISCOUNT</td><td>{{ number_format($invoice->discount, 2, '.', ',') }}</td></tr>
            @if($invoice->total_vat_inclusive > 0)
                <tr><td colspan="4"></td><td>TOTAL INCL. VAT</td><td>{{ number_format($invoice->total_vat_inclusive, 2, '.', ',') }}</td></tr>
            @else
                <tr><td colspan="4"></td><td>TOTAL EXCL. VAT</td><td>{{ number_format($invoice->total_vat_exclusive, 2, '.', ',') }}</td></tr>
            @endif
            <tr><td colspan="4"></td><td>WITHHOLDING TAX (5%)</td><td>{{ number_format($invoice->withholding_tax, 2, '.', ',') }}</td></tr>
            <tr><td colspan="4"></td><td>AMOUNT DUE</td><td><b style="color: #9c0505;">{{ $invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{ number_format($invoice->amount_due, 2, '.', ',') }}</b></td></tr>
        </tbody>
    </table>

    <!-- Payment Status Section -->
    @if ($invoice->payment_status == 1)
    <table style="margin-top: 20px; width: 100%;">
        <tr>
            <td colspan="4"></td>
            <td><b>PAID AMOUNT</b></td>
            <td style="color: #0066cc;"><b>{{ $invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{ number_format($invoice->amount_paid, 2, '.', ',') }}</b></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td><b>UNPAID AMOUNT</b></td>
            <td style="color: #cc0000;"><b>{{ $invoice->currency_id == 2 ? 'USD $' : 'TZs.' }} {{ number_format($invoice->amount_due, 2, '.', ',') }}</b></td>
        </tr>
    </table>
    @endif

    <!-- 🧾 Footer -->
    <div class="footer">
        <h2>Payment Method:</h2>
        @if($invoice->vat > 0)
            {{-- VAT Invoice: Show NMB Account based on currency --}}
            <p>NMB BANK: KURASINI BRANCH </p>
            @if($invoice->currency_id == 2)
                {{-- USD Currency --}}
                <p>NMB ACC USD: <b>23610021602</b></p>
            @else
                {{-- TSH Currency --}}
                <p>NMB ACC TSH: <b>23610021600</b></p>
            @endif
            <p>NAME: <b>NARET COMPANY LIMITED</b></p>
        @else
            {{-- Non-VAT Invoice: Show NBC Account --}}
            <p>NBC: SAMORA BRANCH </p>
            <p>ACC: <b>012103024077</b></p>
            <p>Name: <b>NARET FUMIGATION AND GENERAL CLEANNESS</b></p>
        @endif

        <hr style="height:2px; background-color:#bbb;">

        <b>Remarks:</b>
        <p>We assure you of our best quality and affordable services. If this invoice is not disputed within 7 days, it is regarded as approved.</p>
    </div>
</body>
</html>
