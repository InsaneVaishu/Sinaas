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
        if(Schema::hasTable('option_customizes')) return;
        Schema::defaultStringLength(191);
        Schema::create('option_customizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customize_id')->constrained('customizes')->cascadeOnDelete();
            $table->string('stock');
            $table->string('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_customizes');
    }
};
