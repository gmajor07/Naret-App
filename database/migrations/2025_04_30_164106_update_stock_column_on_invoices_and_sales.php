<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // ✅ Add stock_already_reduced to invoices (default false / 0)
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('stock_already_reduced')->default(false)->after('invoice_status');
        });

        // ❌ Drop stock_already_reduced from sales
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'stock_already_reduced')) {
                $table->dropColumn('stock_already_reduced');
            }
        });
    }

    public function down()
    {
        // ❌ Remove from invoices
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('stock_already_reduced');
        });

        // ✅ Restore to sales if needed
        Schema::table('sales', function (Blueprint $table) {
            $table->boolean('stock_already_reduced')->default(false);
        });
    }
};

