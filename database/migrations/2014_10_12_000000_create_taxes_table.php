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
        if(Schema::hasTable('taxes')) return;
        Schema::defaultStringLength(191);
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tax_value');
            $table->string('type');
            $table->foreignId('zone_id')->nullable()->cascadeOnDelete();
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
