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
        if(Schema::hasTable('product_inventories')) return;
        Schema::create('product_inventories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('inventory_id');
            $table->bigInteger('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_inventories');
    }
};
