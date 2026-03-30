<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('invoice_id');
            //$table->unsignedBigInteger('product_id');
             //$table->unsignedBigInteger('customer_id');
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->foreignId('customer_id')->constrained('customers');
           /*  $table->foreignId('product_id')->constrained('products');
            $table->string('description');
           $table->integer('quantity');
            $table->decimal("unit_cost",19,2);
            $table->decimal("price",19,2);
            $table->integer('currency');  */
            $table->string('comment')->default('');
            $table->decimal('total_amount', 10, 2);
            $table->unsignedBigInteger('approved_by');
            $table->Integer('rejected');
            $table->Integer('stock_already_reduced');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
