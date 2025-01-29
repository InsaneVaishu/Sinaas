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
        if(Schema::hasTable('kitchens')) return;
        Schema::create('kitchens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kitchenname_id')->nullable()->constrained('kitchen_names')->cascadeOnDelete();
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kitchens');
    }
};
