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
        if(Schema::hasTable('tags')) return;
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagname_id')->nullable()->constrained('tag_names')->cascadeOnDelete();
            $table->string('tag_image')->nullable();
            $table->boolean('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
