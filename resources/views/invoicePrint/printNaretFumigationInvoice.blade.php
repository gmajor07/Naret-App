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

.footer {
    margin-top: 30px;
}

.amount-due {
    color: #a00000;
    font-weight: bold;
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
    NARET FUMIGATION AND GENERAL CLEANNESS
</div>

<!-- HEADER -->
<table class="header">
<tr>
    <td class="title">
        {{ $status == 'invoice' ? 'INVOICE' : 'PROFORMA INVOICE' }}
    </td>

    <td class="logo">
        <img src="{{ public_path('assets/img/naret_fummigation.jpg') }}" width="160">
    </td>
</tr>
</table>

<!-- INFO -->
<table class="info section">
<tr>

<td width="40%">
    <b>{{ $invoice->customer->name }}</b><br>
    Email: {{ $invoice->customer->email }}<br>
    Phone Number: {{ $invoice->customer->phone }}<br>
    Location: {{ $invoice->customer->location }}<br>
    Tin Number: {{ $invoice->customer->tin_number }}<br>
    VRN: {{ $invoice->customer->vrn }}
</td>

<td width="20%" class="divider">
    <b>Invoice Number:</b> {{ $invoice->invoice_number }}<br>
    <b>Invoice Date:</b> {{ $invoice->created_at->format('d/m/Y') }}<br>
    <b>Due Date:</b> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}<br>
    <b>Customer PO Number:</b> {{ $invoice->order->po_number ?? 'N/A' }}
</td>

<td width="40%">
    <b>NARET FUMIGATION AND GENERAL CLEANNESS</b><br>
    Opposite of Gate 5 stand, Shimo la Udongo Road, Kurasini,<br>
    P.O.Box 6230, Dar es Salaam, Tanzania.<br>
    Phone Number: 0753995084/0754689775<br>
    Email: naret@naret.co.tz<br>
    Tin Number: 155884452<br>
    VRN: +40039930
</td>

</tr>
</table>

<!-- TABLE -->
<table class="items section">
<thead>
<tr>
    <th>S/N</th>
    <th>Title</th>
    <th class="text-left">Description</th>
    <th>Quantity</th>
    <th>Unit Price</th>
    <th>Amount</th>
</tr>
</thead>

<tbody>

@php $i = 1; @endphp

@foreach ($invoice->order->fumigations as $item)
<tr>
    <td>{{ $i++ }}</td>
    <td>Fumigation</td>
    <td class="text-left">{{ $item->description }}</td>
    <td>{{ $item->item_quantity }}</td>
    <td>{{ number_format($item->unit_price,2) }}</td>
    <td>{{ number_format($item->unit_price * $item->item_quantity,2) }}</td>
</tr>
@endforeach

<!-- TOTALS INSIDE TABLE -->
<tr>
    <td colspan="4"></td>
    <td>TOTAL INCLUSIVE OF VAT</td>
    <td>{{ number_format($invoice->vat,2) }}</td>
</tr>

<tr>
    <td colspan="4"></td>
    <td>TOTAL EXCLUSIVE OF VAT</td>
    <td>{{ number_format($invoice->total_vat_exclusive,2) }}</td>
</tr>

<tr>
    <td colspan="4"></td>
    <td>VAT (18%)</td>
    <td>{{ number_format($invoice->vat,2) }}</td>
</tr>

<tr>
    <td colspan="4"></td>
    <td>WITHHOLDING TAX (5%)</td>
    <td>{{ number_format($invoice->withholding_tax,2) }}</td>
</tr>

<tr>
    <td colspan="4"></td>
    <td>DISCOUNT</td>
    <td>{{ number_format($invoice->discount,2) }}</td>
</tr>

<tr>
    <td colspan="4"></td>
    <td><b>AMOUNT DUE</b></td>
    <td class="amount-due">
        TZs. {{ number_format($invoice->amount_due,2) }}
    </td>
</tr>

</tbody>
</table>

<!-- FOOTER -->
<div class="footer">
    <h3>Payment Method:</h3>

    NBC BANK: SAMORA BRANCH<br>
    ACC: 012103024077<br>
    Name: NARET FUMIGATION AND GENERAL CLEANNESS

    <hr>

    <b>Remarks</b><br>
    We assure you of our best quality and affordable, miscellaneous port and fumigation services all time. 
    If this invoice is not disputed within 7 days, it is regarded as approved.
</div>

</body>
</html>