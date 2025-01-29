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
        if(Schema::hasTable('order_statuses')) return;
        Schema::defaultStringLength(191);
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->integer('language_id')->nullable();
            $table->string('name');
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_statuses');
    }
};
