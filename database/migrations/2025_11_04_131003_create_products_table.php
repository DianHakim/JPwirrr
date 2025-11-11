<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // id
            $table->string('prd_name');
            $table->decimal('prd_price', 12, 2)->default(0);
            $table->integer('prd_stock')->default(0);
            $table->string('prd_color')->nullable();
            $table->string('prd_size')->nullable();
            $table->string('prd_photo')->nullable();
            $table->unsignedBigInteger('pdc_id');
            $table->foreign('pdc_id')->references('id')->on('product_categories')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
