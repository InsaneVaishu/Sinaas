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
        if(Schema::hasTable('option_stocks')) return;
        Schema::create('option_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_id')->nullable()->constrained('options')->cascadeOnDelete();
            $table->string('stock_price');
            $table->string('stock_deduction');
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_stocks');
    }
};
