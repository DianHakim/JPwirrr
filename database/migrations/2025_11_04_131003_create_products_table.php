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
        Schema::create('products', function (Blueprint $table) {
            $table->id('prd_id');
            $table->string('prd_name');
            $table->decimal('prd_price', 12, 2)->default(0);
            $table->integer('prd_stock')->default(0);
            $table->string('prd_color')->nullable();
            $table->string('prd_size')->nullable();
            $table->foreignId('pdc_id')
                ->nullable()
                ->constrained('product_categories')
                ->nullOnDelete();
            $table->string('prd_img_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
