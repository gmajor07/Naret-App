<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        @page {
            margin: 45px;
        }

        body {
            color: #000;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
            margin: 0;
        }

        .watermark {
            color: rgba(0, 0, 0, 0.05);
            font-size: 45px;
            font-weight: bold;
            left: 0;
            line-height: 1.22;
            position: fixed;
            text-align: center;
            top: 33%;
            transform: rotate(-45deg);
            width: 100%;
            z-index: -1;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .top-table td {
            border: 1px solid #ddd;
            height: 178px;
            vertical-align: middle;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            padding-left: 37px;
        }

        .logo-cell {
            text-align: center;
        }

        .logo-cell img {
            height: 160px;
            width: 268px;
        }

        .info-table {
            margin-top: 8px;
        }

        .info-table td {
            border: 1px solid #ddd;
            line-height: 1.2;
            padding: 7px;
            vertical-align: top;
        }

        .customer-cell {
            width: 40%;
        }

        .details-cell {
            border-right: 2px solid #000 !important;
            font-weight: normal;
            width: 10.5%;
        }

        .company-cell {
            width: 49.5%;
        }

        .muted {
            color: rgb(70, 65, 65);
        }

        .items-table {
            margin-top: 8px;
        }

        .items-table th {
            background: #1b5e85;
            border: 1px solid #ddd;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
            padding: 8px 5px;
            text-align: center;
        }

        .items-table td {
            border: 1px solid #ddd;
            font-size: 12px;
            padding: 7px 5px;
            text-align: center;
            vertical-align: middle;
        }

        .items-table tbody tr {
            page-break-inside: avoid;
        }

        .sn-col {
            width: 5%;
        }

        .title-col {
            width: 10%;
        }

        .description-col {
            width: 44%;
        }

        .quantity-col {
            width: 9%;
        }

        .unit-col {
            width: 18%;
        }

        .amount-col {
            width: 14%;
        }

        .charge-col {
            width: 17%;
        }

        .description-text {
            line-height: 1.25;
            text-align: center;
            word-break: break-word;
        }

        .totals-label {
            font-size: 12px !important;
            line-height: 1.25;
            text-transform: uppercase;
        }

        .total-value {
            font-weight: bold;
        }

        .zero-value {
            color: #2b8dcc;
            font-weight: bold;
        }

        .amount-due {
            color: #9c0505;
            font-weight: bold;
        }

        .footer {
            margin-top: 28px;
        }

        .footer h2 {
            font-size: 16px;
            margin: 0 0 16px;
        }

        .footer p {
            font-size: 11px;
            margin: 0 0 10px;
        }

        .footer-rule {
            background: #bbb;
            border: 0;
            height: 2px;
            margin: 10px 0 8px;
        }

        .remarks-title {
            color: #3c8dbc;
            font-weight: bold;
        }
    </style>
</head>
<body>
@php
    $orderTypeId = (int) ($invoice->order?->type_id ?? 0);
    $usesCompanyInvoice = in_array($orderTypeId, [1, 2, 3], true) && (bool) $invoice->is_non_vat;
    $companyName = $usesCompanyInvoice ? 'NARET COMPANY LIMITED' : 'NARET FUMIGATION AND GENERAL CLEANNESS';
    $companyDisplayName = $usesCompanyInvoice ? 'NARET COMPANY LIMITED' : 'NARET FUMIGATION AND GENERAL CLEANNESS.';
    $logoPath = $usesCompanyInvoice ? 'assets/img/naret_company.jpg' : 'assets/img/narets.jpg';
    $currencyLabel = $invoice->currency_id == 2 ? 'USD $' : 'TZs.';
    $poNumber = $invoice->order?->po_number ?: 'N/A';
    $isProforma = $status !== 'invoice';
    $totalInclusive = (float) $invoice->total_vat_inclusive;
    $totalExclusive = (float) $invoice->total_vat_exclusive;
@endphp

<div class="watermark">
    @if($usesCompanyInvoice)
        NARET COMPANY<br>LIMITED
    @else
        NARET FUMIGATION<br>AND GENERAL<br>CLEANNESS
    @endif
</div>

<table class="top-table">
    <tr>
        <td style="width: 60%;">
            <div class="invoice-title">{{ $isProforma ? 'PROFOMA INVOICE' : 'INVOICE' }}</div>
        </td>
        <td class="logo-cell" style="width: 40%;">
            <img src="{{ public_path($logoPath) }}" alt="Naret">
        </td>
    </tr>
</table>

<table class="info-table">
    <tr>
        <td class="customer-cell">
            <strong>{{ $invoice->customer->name }}</strong><br>
            <span class="muted">
                Email: {{ $invoice->customer->email }}<br>
                Phone Number: {{ $invoice->customer->phone }}<br>
                Location: {{ $invoice->customer->location }}<br>
                Tin Number: {{ $invoice->customer->tin_number }}<br>
                VRN: {{ $invoice->customer->vrn ?: 'N/A' }}
            </span>
        </td>
        <td class="details-cell">
            <strong>Invoice Number:</strong><br>
            {{ $invoice->invoice_number }}<br>
            <strong>Invoice Date:</strong><br>
            {{ $invoice->created_at->format('d/m/Y') }}<br>
            <strong>Due Date:</strong><br>
            {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}<br>
            <strong>Customer PO Number:</strong><br>
            {{ $poNumber }}<br>
            00:00:00
        </td>
        <td class="company-cell">
            <strong>{{ $companyDisplayName }}</strong><br>
            <span class="muted">
                Opposite of Gate 5 stand, Shimo la Udongo Road, Kurasini,<br>
                P.O.Box 6230, Dar es Salaam, Tanzania.<br>
                Phone Number: 0753995084/0754689775<br>
                Email: naret@naret.co.tz<br>
                Tin Number: 155884452<br>
                VRN: +40039930
            </span>
        </td>
    </tr>
</table>

<table class="items-table">
    <thead>
        @if($invoice->invoice_type == 3)
            <tr>
                <th class="sn-col">S/N</th>
                <th class="description-col">Description</th>
                <th class="charge-col">Labour Charges</th>
                <th class="charge-col">Administration Fee</th>
                <th class="quantity-col">Quantity</th>
                <th class="amount-col">Amount</th>
            </tr>
        @else
            <tr>
                <th class="sn-col">S/N</th>
                <th class="title-col">Title</th>
                <th class="description-col">Description</th>
                <th class="quantity-col">Quantity</th>
                <th class="unit-col">Unit Price</th>
                <th class="amount-col">Amount</th>
            </tr>
        @endif
    </thead>
    <tbody>
        @if($invoice->invoice_type == 1)
            @foreach ($invoice->order->products->reverse() as $key => $product)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td class="description-text">{{ $product->description }}@if($invoice->order->description) {{ $invoice->order->description }} @endif</td>
                    <td>{{ $product->order_products->quantity }}</td>
                    <td>{{ number_format($product->unity_price, 2, '.', ',') }}</td>
                    <td>{{ number_format($product->unity_price * $product->order_products->quantity, 2, '.', ',') }}</td>
                </tr>
            @endforeach
        @elseif($invoice->invoice_type == 3)
            @foreach ($invoice->order->casual_labour->reverse() as $key => $casual)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="description-text">{{ $casual->description }}</td>
                    <td>{{ number_format($casual->labour_charge, 2, '.', ',') }}</td>
                    <td>{{ number_format($casual->administration_fee, 2, '.', ',') }}</td>
                    <td>{{ $casual->quantity }}</td>
                    <td>{{ number_format(($casual->labour_charge + $casual->administration_fee) * $casual->quantity, 2, '.', ',') }}</td>
                </tr>
            @endforeach
        @else
            @foreach ($invoice->order->fumigations->reverse() as $key => $fumigation)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>Fumigation</td>
                    <td class="description-text">{{ $fumigation->description }}</td>
                    <td>{{ $fumigation->item_quantity }}</td>
                    <td>{{ number_format($fumigation->unit_price, 2, '.', ',') }}</td>
                    <td>{{ number_format($fumigation->unit_price * $fumigation->item_quantity, 2, '.', ',') }}</td>
                </tr>
            @endforeach
        @endif

        <tr>
            <td colspan="4"></td>
            <td class="totals-label">TOTAL INCLUSIVE OF VAT:</td>
            <td class="{{ $totalInclusive == 0.0 ? 'zero-value' : 'total-value' }}">{{ number_format($totalInclusive, 2, '.', ',') }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td class="totals-label">TOTAL EXCLUSIVE OF VAT</td>
            <td class="{{ $totalExclusive == 0.0 ? 'zero-value' : 'total-value' }}">{{ number_format($totalExclusive, 2, '.', ',') }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td class="totals-label">{{ $invoice->is_non_vat ? 'NON VAT' : 'VAT (18%)' }}</td>
            <td class="{{ (float) $invoice->vat == 0.0 ? 'zero-value' : 'total-value' }}">{{ number_format($invoice->vat, 2, '.', ',') }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td class="totals-label">WITHHOLDING TAX (5%)</td>
            <td class="total-value">{{ number_format($invoice->withholding_tax, 2, '.', ',') }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td class="totals-label">DISCOUNT</td>
            <td class="total-value">{{ number_format($invoice->discount, 2, '.', ',') }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td class="totals-label">AMOUNT DUE</td>
            <td class="amount-due">{{ $currencyLabel }}<br>{{ number_format($invoice->amount_due, 2, '.', ',') }}</td>
        </tr>
    </tbody>
</table>

@if ($invoice->payment_status == 1)
    <table class="items-table" style="margin-top: 14px;">
        <tr>
            <td colspan="4"></td>
            <td class="totals-label">PAID AMOUNT</td>
            <td class="zero-value">{{ $currencyLabel }} {{ number_format($invoice->amount_paid, 2, '.', ',') }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td class="totals-label">UNPAID AMOUNT</td>
            <td class="amount-due">{{ $currencyLabel }} {{ number_format($invoice->amount_due, 2, '.', ',') }}</td>
        </tr>
    </table>
@endif

<div class="footer">
    <h2>Payment Method:</h2>
    @if($usesCompanyInvoice)
        <p>NMB BANK: KURASINI BRANCH</p>
        @if($invoice->currency_id == 2)
            <p>NMB ACC USD: <strong>23610021602</strong></p>
        @else
            <p>NMB ACC TSH: <strong>23610021600</strong></p>
        @endif
        <p>NAME: <strong>NARET COMPANY LIMITED</strong></p>
    @else
        <p>NBC BANK: SAMORA BRANCH</p>
        <p>ACC: <strong>012103024077</strong></p>
        <p>Name: <strong>NARET FUMIGATION AND GENERAL CLEANNESS</strong></p>
    @endif

    <hr class="footer-rule">

    <div class="remarks-title">Remarks</div>
    <p>We assure you of our best quality and affordable, miscellaneous port and fumigation services all time. If this invoice is not disputed within 7 days, it is regarded as approved.</p>
</div>
</body>
</html>
