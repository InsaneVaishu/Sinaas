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
        if(Schema::hasTable('products')) return;
        Schema::defaultStringLength(191);
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productname_id')->nullable()->constrained('products_names')->cascadeOnDelete();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->string('price');
            $table->string('image');
            $table->integer('tax_id');
            $table->boolean('status')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
