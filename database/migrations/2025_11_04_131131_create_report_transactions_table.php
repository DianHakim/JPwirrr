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
        Schema::create('report_transactions', function (Blueprint $table) {
            $table->id('dtr_id');

            $table->foreignId('trs_id')
                ->constrained('transactions')
                ->cascadeOnDelete();

            $table->foreignId('prd_id')
                ->nullable()
                ->constrained('products')
                ->nullOnDelete();

            $table->decimal('dtr_subtotal', 12, 2);
            $table->date('dtr_period'); // contoh format periode: 2025-11-01

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_transactions');
    }
};
