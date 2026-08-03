<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Invoice;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueNonVatExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithEvents, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $invoices;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if (!$this->invoices) {
            $this->invoices = Invoice::whereBetween('created_at', [$this->startDate, $this->endDate])
                                     ->where('invoice_status', '!=', 3)
                                     ->where('tax_type', Invoice::TAX_TYPE_EXEMPT)
                                     ->whereHas('order.products', function ($query) {
                                         $query->whereRaw('UPPER(TRIM(name)) = ?', ['ALUMINIUM PHOSPHIDE 57%']);
                                     })
                                     ->with(['customer', 'order.products'])
                                     ->latest('created_at')
                                     ->get();
        }

        return $this->invoices;
    }

    public function headings(): array
    {
        return ["Date","Customer Name", "Order Number", "Invoice", "Product", "Description", "Amount"];
    }

    public function map($invoice): array
    {
        $productNames = $invoice->order?->products
            ->pluck('name')
            ->filter()
            ->join(', ');
        $descriptions = $invoice->order?->products
            ->pluck('description')
            ->filter()
            ->join(', ');

        return [
            Carbon::parse($invoice->created_at)->format('d-m-Y'),
            $invoice->customer->name,
            $invoice->order->order_number,
            $invoice->invoice_number,
            $productNames,
            $descriptions,
            number_format($invoice->total_amount, 2),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $total = $this->collection()->sum('total_amount');

                // Get the last row number
                $lastRow = count($this->collection()) + 2;

                // Set total amount row
                $event->sheet->setCellValue('F' . $lastRow, 'Total');
                $event->sheet->setCellValue('G' . $lastRow, number_format($total, 2));
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->collection()) + 2;
        return [
            // Bold the headers (First row)
            1 => ['font' => ['bold' => true]],

            // Bold the "Total" column
            "F{$lastRow}" => ['font' => ['bold' => true]],
            "G{$lastRow}" => ['font' => ['bold' => true]],
        ];
    }
}
