<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\CasualLabour;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CasualLabourExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithEvents, WithStyles
{

    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = CasualLabour::with('orders.customer', 'orders.invoice');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ["Date", "Order Start Date", "Order Number", "Customer Name", "Description", "Labour Charge", "Administration Fee", "Quantity", "Total Amount"];
    }

    public function map($casual): array
    {
        $order = $casual->orders->first();
        return [
            Carbon::parse($casual->created_at)->format('d-m-Y'),
            $order ? Carbon::parse($order->order_date)->format('d-m-Y') : 'N/A',
            $order->order_number ?? 'N/A',
            $order->customer->name ?? 'N/A',
            $casual->description,
            number_format($casual->labour_charge, 2),
            number_format($casual->administration_fee, 2),
            $casual->quantity,
            number_format(($casual->labour_charge + $casual->administration_fee) * $casual->quantity, 2),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $total = $this->collection()->sum(function ($casual) {
                    return ($casual->labour_charge + $casual->administration_fee) * $casual->quantity;
                });
                // Get the last row number
                $lastRow = count($this->collection()) + 2;

                // Set total amount row
                $event->sheet->setCellValue('H' . $lastRow, 'Total');
                $event->sheet->setCellValue('I' . $lastRow, number_format($total, 2));
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
            "H{$lastRow}" => ['font' => ['bold' => true]],
            "I{$lastRow}" => ['font' => ['bold' => true]],
        ];
    }
}
