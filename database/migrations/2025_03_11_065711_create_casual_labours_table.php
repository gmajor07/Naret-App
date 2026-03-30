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
        Schema::create('casual_labours', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->decimal('labour_charge', 10, 2);
            $table->decimal('administration_fee', 10, 2);
            $table->integer('quantity');
            $table->decimal('vat', 10, 2);
            $table->decimal('payable_amount', 10, 2);
            $table->integer('status');
            $table->unsignedBigInteger('expense_id');
            $table->date('date');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casual_labours');
    }
};
