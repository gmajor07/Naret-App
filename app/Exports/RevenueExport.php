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

class RevenueExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithEvents, WithStyles
{

    protected $startDate;
    protected $endDate;
    protected $cachedCollection;

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
        if (!$this->cachedCollection) {
            $this->cachedCollection = Invoice::whereBetween('created_at', [$this->startDate, $this->endDate])
                ->where('invoice_status', '!=', 3)
                ->with(['customer', 'order.products'])
                ->latest('created_at')
                ->get();
        }
        return $this->cachedCollection;
    }

    public function headings(): array
    {
        return ["Date","Customer Name", "Order Number", "Invoice", "Tax Type", "Description", "Amount"];
    }

    public function map($invoice): array
    {
        $description = $invoice->order?->description ?: $invoice->order?->products
            ->pluck('description')
            ->filter()
            ->join(', ');

        return [
            Carbon::parse($invoice->created_at)->format('d-m-Y'),
            $invoice->customer->name,
            $invoice->order->order_number,
            $invoice->invoice_number,
            $invoice->taxLabel(),
            $description,
            number_format($invoice->total_amount, 2),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                //$total = Sale::whereBetween('created_at', [$this->startDate, $this->endDate])->where('approved_by',1)->sum('total_amount');
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
        //$lastRow = $sheet->getHighestRow();
        $lastRow = count($this->collection()) + 2;
        return [
            // Bold the headers (First row)
            1 => ['font' => ['bold' => true]],

            // Bold the "Total" column (Assuming column "C" contains the total amounts)
            "F{$lastRow}" => ['font' => ['bold' => true]],
            "G{$lastRow}" => ['font' => ['bold' => true]],
            //'C' => ['font' => ['bold' => true]],
        ];
    }

}
