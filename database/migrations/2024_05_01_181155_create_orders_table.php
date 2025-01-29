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
        if(Schema::hasTable('orders')) return;
        Schema::defaultStringLength(191);
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->integer('user_id');
            $table->integer('language_id')->nullable();
            $table->timestamp('order_time')->nullable();
            $table->integer('oder_type')->nullable();
            $table->string('order_total')->nullable();
            $table->string('order_currency')->nullable();
            $table->integer('order_added');
            $table->timestamp('order_modified');
            $table->timestamp('order_address')->nullable();
            $table->string('order_geo')->nullable();
            $table->string('order_note')->nullable();
            $table->integer('order_alert')->nullable();
            $table->integer('payment_type')->nullable();
            $table->integer('payment_status')->nullable();
            $table->integer('order_status_id');
            $table->boolean('status')->default(true);
        });
    }

    



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
