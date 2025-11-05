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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id('tdt_id');
            $table->foreignId('trs_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('prd_id')->constrained('products')->restrictOnDelete();
            $table->integer('qty')->default(1);
            $table->decimal('price_at_sale', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
