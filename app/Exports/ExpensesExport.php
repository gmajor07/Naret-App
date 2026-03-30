<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Expense;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithStyles;

class ExpensesExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithEvents,WithStyles
{
    protected $startDate;
    protected $endDate;

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
        return Expense::whereBetween('date', [$this->startDate, $this->endDate])->get();
    }

    public function headings(): array
    {
        return ["Date","Description", "Amount"];
    }

    public function map($expense): array
    {
        return [
            Carbon::parse($expense->date)->format('d-m-Y'),
            $expense->description,
            number_format($expense->amount, 2),
           /*  $expense->date, */
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $total = Expense::whereBetween('date', [$this->startDate, $this->endDate])->sum('amount');

                // Get the last row number
                $lastRow = count($this->collection()) + 2;

                // Set total amount row
                $event->sheet->setCellValue('B' . $lastRow, 'Total');
                $event->sheet->setCellValue('C' . $lastRow, number_format($total, 2));
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
            "A{$lastRow}" => ['font' => ['bold' => true]],
            "B{$lastRow}" => ['font' => ['bold' => true]],
            //'C' => ['font' => ['bold' => true]],
        ];
    }
}
