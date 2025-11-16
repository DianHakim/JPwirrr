<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('trs_code')->unique();
            $table->decimal('trs_subtotal', 12, 2)->default(0);
            $table->decimal('trs_discount', 12, 2)->default(0);
            $table->decimal('trs_total', 12, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->decimal('cash', 15, 2)->default(0);
            $table->decimal('change', 15, 2)->default(0);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
