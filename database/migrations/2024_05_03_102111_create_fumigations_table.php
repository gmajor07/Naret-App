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
        Schema::create('fumigations', function (Blueprint $table) {
            $table->id();
            $table->string('fumigation_number');
           /*  $table->foreignId('customer_id')->constrained('customers'); */
            $table->string('description');
            $table->unsignedInteger('item_quantity');
            $table->decimal('unit_price', 10, 2);
            $table->date('fumigation_date');
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fumigations');
    }
};
