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
        Schema::create('carousel_slides', function (Blueprint $table) {
            $table->id();
            $table->string('subtitle')->nullable(); // e.g. "Warzone Arena"
            $table->string('title'); // e.g. "Man of the Match"
            
            // Stats Slots
            $table->string('box1_label')->nullable()->default('1st');
            $table->string('box1_value')->nullable()->default('---');
            
            $table->string('box2_label')->nullable()->default('2nd');
            $table->string('box2_value')->nullable()->default('---');
            
            $table->string('box3_label')->nullable()->default('3rd');
            $table->string('box3_value')->nullable()->default('---');
            
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carousel_slides');
    }
};
