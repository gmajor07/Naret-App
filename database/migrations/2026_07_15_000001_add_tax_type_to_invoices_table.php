<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('tax_type', 20)->default('without_vat')->after('is_non_vat');
            $table->index('tax_type');
        });

        DB::table('invoices')->where('vat', '>', 0)->update(['tax_type' => 'vat']);
        DB::table('invoices')->where('is_non_vat', true)->update(['tax_type' => 'exempt']);
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['tax_type']);
            $table->dropColumn('tax_type');
        });
    }
};
