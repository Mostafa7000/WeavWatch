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
        Schema::create('iron_defect_dress_size', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dress_id')->constrained('dresses')->cascadeOnDelete();
            $table->foreignId('size_id')->constrained('sizes')->cascadeOnDelete();
            $table->foreignId('defect_id')->constrained('iron_defects')->cascadeOnDelete();
            $table->integer('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iron_defect_dress_size');
    }
};
