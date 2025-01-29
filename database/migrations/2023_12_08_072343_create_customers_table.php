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
        if(Schema::hasTable('customers')) return;
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('parent_id')->constrained('customers')->cascadeOnDelete();
            $table->date('date_of_birth');
            $table->unsignedInteger('gender')->default(0);
            $table->unsignedBigInteger('roll_id')->default(0);
            $table->foreignId('payout_id')->constrained('payout')->cascadeOnDelete();            
            $table->string('address');
            $table->boolean('status')->default(true);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
