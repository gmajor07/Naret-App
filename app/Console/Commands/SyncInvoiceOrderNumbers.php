<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;

class SyncInvoiceOrderNumbers extends Command
{
    protected $signature = 'invoices:sync-order-numbers {--dry-run : Show mismatches without updating invoices}';

    protected $description = 'Sync invoice numbers with their related order numbers';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $checked = 0;
        $changed = 0;

        Invoice::with('order')->chunkById(100, function ($invoices) use ($dryRun, &$checked, &$changed) {
            foreach ($invoices as $invoice) {
                $checked++;

                if (! $invoice->order || ! preg_match('/^(\d{4})-ORD-(\d{4})$/', $invoice->order->order_number, $matches)) {
                    continue;
                }

                $expectedNumber = $matches[1] . '-' . $matches[2];

                if ($invoice->invoice_number === $expectedNumber) {
                    continue;
                }

                $changed++;
                $this->line("Invoice {$invoice->id}: {$invoice->invoice_number} -> {$expectedNumber}");

                if (! $dryRun) {
                    $invoice->forceFill(['invoice_number' => $expectedNumber])->save();
                }
            }
        });

        $action = $dryRun ? 'would be updated' : 'updated';
        $this->info("Checked {$checked} invoices; {$changed} {$action}.");

        return self::SUCCESS;
    }
}
