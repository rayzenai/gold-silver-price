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
        $tableName = config('gold-silver-price.table_name', 'gold_prices');

        if (! Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->date('date')->unique();
                $table->integer('gold_per_tola')->comment('Gold price per tola in Rs.');
                $table->integer('gold_per_10g')->comment('Gold price per 10 grams in Rs.');
                $table->integer('silver_per_tola')->comment('Silver price per tola in Rs.');
                $table->integer('silver_per_10g')->comment('Silver price per 10 grams in Rs.');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = config('gold-silver-price.table_name', 'gold_prices');

        Schema::dropIfExists($tableName);
    }
};
