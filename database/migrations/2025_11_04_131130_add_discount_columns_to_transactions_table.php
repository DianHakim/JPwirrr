<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->enum('discount_type', ['none', 'percent', 'nominal'])
                  ->default('none')
                  ->after('trs_total');

            $table->decimal('discount_percent', 5, 2)
                  ->nullable()
                  ->after('discount_type');

            $table->decimal('discount_nominal', 12, 2)
                  ->default(0)
                  ->after('discount_percent');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'discount_type',
                'discount_percent',
                'discount_nominal'
            ]);
        });
    }
};
