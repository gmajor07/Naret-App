<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Sale;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueWithoutVatExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithEvents, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $sales;

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
        //return Sale::whereBetween('created_at', [$this->startDate, $this->endDate])->where('approved_by',1)->get();
        //return $sales = Sale::where(fn($q) => 
                      // $q->whereBetween('created_at', [$this->startDate, $this->endDate])
                     // ->where('approved_by', 1) )->get();
         if (!$this->sales) {
        $this->sales = Sale::whereBetween('created_at', [$this->startDate, $this->endDate])
                           ->where('approved_by', 1)
                           ->whereHas('invoice', function ($query) {
                               $query->where('vat', 0); // adjust as per your column
                           })
                           ->get();
    }

    return $this->sales;
    
    }

    public function headings(): array
    {
        return ["Date","Customer Name", "Order Number", "Invoice","Amount"];
    }

    public function map($sale): array
    {
        return [
            Carbon::parse($sale->updated_at)->format('d-m-Y'),
            $sale->customer->name,
            $sale->order->order_number,
            $sale->invoice->invoice_number,
            number_format($sale->total_amount, 2),
           // Carbon::parse($sale->date)->format('d-m-Y'),
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
                $event->sheet->setCellValue('D' . $lastRow, 'Total');
                $event->sheet->setCellValue('E' . $lastRow, number_format($total, 2));
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
            "D{$lastRow}" => ['font' => ['bold' => true]],
            "E{$lastRow}" => ['font' => ['bold' => true]],
            //'C' => ['font' => ['bold' => true]],
        ];
    }
}
