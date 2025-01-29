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
        if(Schema::hasTable('image_names')) return;
        Schema::defaultStringLength(191);
        Schema::create('image_names', function (Blueprint $table) {
            $table->id();
            $table->string('image');
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
        Schema::dropIfExists('image_names');
    }
};
