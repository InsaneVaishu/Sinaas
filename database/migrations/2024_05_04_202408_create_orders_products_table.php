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
        if(Schema::hasTable('orders_products')) return;
        Schema::defaultStringLength(15);
        Schema::create('orders_products', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('product_id');
            $table->integer('stock_id');
            $table->integer('name_id');
            $table->integer('kitchen_id');
            $table->string('price');
            $table->integer('quantity');
            $table->string('total');
            $table->integer('tax_id');
            $table->string('tax');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_products');
    }
};
