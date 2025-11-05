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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('trs_id');
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete(); // kasir / owner yang input
            $table->decimal('trs_subtotal', 12, 2)->default(0);
            $table->decimal('trs_total', 12, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
