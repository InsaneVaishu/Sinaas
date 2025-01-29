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
        if(Schema::hasTable('inventories')) return;
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventoryname_id')->nullable()->constrained('products_names')->cascadeOnDelete();
            $table->string('inventory_image');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
