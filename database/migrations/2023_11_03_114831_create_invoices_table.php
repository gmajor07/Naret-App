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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('invoice_number');
            $table->unsignedBigInteger('order_id');
            $table->decimal('total_amount', 19, 2);
            $table->decimal('total_vat_inclusive', 19, 2);
            $table->decimal("vat",19,2);
            $table->decimal("discount",19,2);
            $table->decimal("total_vat_exclusive",19,2);
            $table->decimal("payable_amount",19,2);
            $table->decimal("amount_paid",19,2);
            $table->decimal("amount_due",19,2);
            $table->unsignedBigInteger('currency_id');
            $table->boolean('payment_status')->default(false);
            $table->integer('invoice_status');
            $table->integer('invoice_type');
            $table->date('due_date');
            $table->integer('withholding_tax');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
