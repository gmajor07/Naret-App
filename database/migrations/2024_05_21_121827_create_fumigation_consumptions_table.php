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
        Schema::create('fumigation_consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumption_id')->constrained('consumptions')->onDelete('cascade');
            $table->foreignId('fumigation_id')->constrained('fumigations')->onDelete('cascade');
            $table->foreignId('fumigator_id')->constrained('users')->onDelete('cascade');
            //$table->unsignedBigInteger('fumigator_id');
            $table->integer('container_quantity');
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('fumigator_id')->references('id')->on('users')->onDelete('cascade');
           //$table->foreign('consumption_id')->references('id')->on('consumptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fumigation_consumptions');
    }
};
