<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice</title>

<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .header td {
        vertical-align: top;
    }

    .title {
        font-size: 22px;
        font-weight: bold;
    }

    .logo {
        text-align: right;
    }

    .section {
        margin-top: 20px;
    }

    .info td {
        vertical-align: top;
        font-size: 12px;
        line-height: 1.6;
    }

    .divider {
        border-right: 2px solid #000;
        padding-right: 10px;
    }

    .items th {
        background: #1b5e85;
        color: #fff;
        padding: 10px;
        border: 1px solid #ddd;
    }

    .items td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .totals td {
        border: none;
        padding: 6px;
    }

    .amount-due {
        color: #a00000;
        font-weight: bold;
    }

    .footer {
        margin-top: 30px;
    }

    .watermark {
        position: fixed;
        top: 40%;
        width: 100%;
        text-align: center;
        font-size: 60px;
        color: rgba(0,0,0,0.05);
        transform: rotate(-45deg);
        z-index: -1;
    }
</style>
</head>

<body>

<!-- WATERMARK -->
<div class="watermark">
    {{ $invoice->vat > 0 
        ? 'NARET COMPANY LIMITED' 
        : 'NARET FUMIGATION AND GENERAL CLEANNESS' }}
</div>

<!-- HEADER -->
<table class="header">
<tr>
    <td class="title">
        {{ $status == 'invoice' ? 'INVOICE' : 'PROFORMA INVOICE' }}
    </td>

    <td class="logo">
        <img src="{{ public_path(
            $invoice->vat > 0 
            ? 'assets/img/naret_company.jpg' 
            : 'assets/img/naret.jpg'
        ) }}" width="160">
    </td>
</tr>
</table>

<!-- INFO SECTION -->
<table class="info section">
<tr>

    <!-- CUSTOMER -->
    <td width="40%">
        <b>{{ $invoice->customer->name }}</b><br>
        Email: {{ $invoice->customer->email }}<br>
        Phone: {{ $invoice->customer->phone }}<br>
        Location: {{ $invoice->customer->location }}<br>
        TIN: {{ $invoice->customer->tin_number }}<br>
        VRN: {{ $invoice->customer->vrn }}
    </td>

    <!-- INVOICE DETAILS -->
    <td width="20%" class="divider">
        @if($invoice->order?->po_number)
            <b>PO:</b> {{ $invoice->order->po_number }}<br>
        @endif
        <b>No:</b> {{ $invoice->invoice_number }}<br>
        <b>Date:</b> {{ $invoice->created_at->format('d/m/Y') }}<br>
        <b>Due:</b> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}
    </td>

    <!-- COMPANY -->
    <td width="40%">
        <b>{{ $invoice->vat > 0 
            ? 'NARET COMPANY LIMITED' 
            : 'NARET FUMIGATION AND GENERAL CLEANNESS' }}</b><br>

        Opposite Gate 5, Shimo la Udongo Road<br>
        P.O Box 6230, Dar es Salaam<br>
        Phone: 0753995084 / 0754689775<br>
        Email: naret@naret.co.tz<br>
        TIN: 155884452<br>
        VRN: +40039930
    </td>

</tr>
</table>

<!-- ITEMS TABLE -->
<table class="items section">
<thead>
<tr>
    <th>S/N</th>
    <th class="text-left">Description</th>
    <th>Labour Charges</th>
    <th>Administration Fee</th>
    <th>Quantity</th>
    <th>Amount</th>
</tr>
</thead>

<tbody>

@php $i = 1; @endphp

{{-- COMPANY DATA --}}
@if(!empty($invoice->order->casual_labour) && count($invoice->order->casual_labour))
    @foreach ($invoice->order->casual_labour as $item)
        <tr>
            <td>{{ $i++ }}</td>
            <td class="text-left">{{ $item->description }}</td>
            <td>{{ number_format($item->labour_charge,2) }}</td>
            <td>{{ number_format($item->administration_fee,2) }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format(($item->labour_charge + $item->administration_fee) * $item->quantity,2) }}</td>
        </tr>
    @endforeach

{{-- FUMIGATION DATA --}}
@elseif(!empty($invoice->order->fumigations) && count($invoice->order->fumigations))
    @foreach ($invoice->order->fumigations as $item)
        <tr>
            <td>{{ $i++ }}</td>
            <td class="text-left">{{ $item->description }}</td>
            <td>{{ number_format($item->unit_price,2) }}</td>
            <td>0.00</td>
            <td>{{ $item->item_quantity }}</td>
            <td>{{ number_format($item->unit_price * $item->item_quantity,2) }}</td>
        </tr>
    @endforeach
@endif

</tbody>
</table>

<!-- TOTALS -->
<table class="totals section">
<tr>
    <td width="70%"></td>
    <td>VAT (18%)</td>
    <td>{{ number_format($invoice->vat,2) }}</td>
</tr>

<tr>
    <td></td>
    <td>Discount</td>
    <td>{{ number_format($invoice->discount,2) }}</td>
</tr>

<tr>
    <td></td>
    <td>Total</td>
    <td>
        {{ number_format(
            $invoice->total_vat_inclusive > 0 
            ? $invoice->total_vat_inclusive 
            : $invoice->total_vat_exclusive
        ,2) }}
    </td>
</tr>

<tr>
    <td></td>
    <td>Withholding Tax (5%)</td>
    <td>{{ number_format($invoice->withholding_tax,2) }}</td>
</tr>

<tr>
    <td></td>
    <td><b>Amount Due</b></td>
    <td class="amount-due">
        {{ $invoice->currency_id == 2 ? 'USD $' : 'TZs.' }}
        {{ number_format($invoice->amount_due,2) }}
    </td>
</tr>
</table>

<!-- FOOTER -->
<div class="footer">
    <h3>Payment Method</h3>

    @if($invoice->vat > 0)
        NMB BANK – KURASINI<br>
        ACC: 23610021600<br>
        NAME: NARET COMPANY LIMITED
    @else
        NBC BANK – SAMORA<br>
        ACC: 012103024077<br>
        NAME: NARET FUMIGATION AND GENERAL CLEANNESS
    @endif

    <hr>

    <b>Remarks:</b><br>
    We assure you of our best quality and affordable services. 
    If not disputed within 7 days, it is approved.
</div>

</body>
</html>