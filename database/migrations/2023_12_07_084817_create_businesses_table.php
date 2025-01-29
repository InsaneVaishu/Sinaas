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
        if(Schema::hasTable('businesses')) return;
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('customers')->cascadeOnDelete();$table->integer('tax_id');
            $table->string('name');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('billing_address');           
            $table->string('shipping_address');
            $table->string('time_zone');
            $table->string('currency');
            $table->string('opening_hours');
            $table->string('delivery_hours');
            $table->string('website');

            $table->string('country_id');
            $table->string('latitude');
            $table->string('longitude');

            $table->string('image');            
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
