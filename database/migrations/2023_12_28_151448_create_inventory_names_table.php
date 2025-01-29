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
        if(Schema::hasTable('inventory_names')) return;
        Schema::defaultStringLength(191);
        Schema::create('inventory_names', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en');
            $table->string('name_es');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_names');
    }
};
