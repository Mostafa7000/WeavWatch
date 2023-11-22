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
        Schema::create('cloth_defect_dress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dress_id')->constrained('dresses')->cascadeOnDelete();
            $table->foreignId('defect_id')->constrained('cloth_defects')->cascadeOnDelete();
            $table->integer('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cloth_defect_dress');
    }
};
