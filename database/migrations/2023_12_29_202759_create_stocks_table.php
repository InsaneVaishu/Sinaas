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
        if(Schema::hasTable('stocks')) return;
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventory_names')->cascadeOnDelete();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->string('buy_price')->nullable();
            $table->string('image')->nullable();
            $table->string('unit_id')->nullable();
            $table->string('quantity')->nullable();
            $table->string('quantity_alert')->nullable();
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
