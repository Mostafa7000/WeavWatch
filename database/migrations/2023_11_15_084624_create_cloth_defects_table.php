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
        Schema::create('cloth_defects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->foreignId('dress_id')->constrained('dresses')->cascadeOnDelete();
            $table->integer('a1')->nullable();
            $table->integer('a2')->nullable();
            $table->integer('a3')->nullable();
            $table->integer('a4')->nullable();
            $table->integer('a5')->nullable();
            $table->integer('a6')->nullable();
            $table->integer('a7')->nullable();
            $table->integer('a8')->nullable();
            $table->integer('a9')->nullable();
            $table->integer('a10')->nullable();
            $table->integer('a11')->nullable();
            $table->integer('a12')->nullable();
            $table->integer('a13')->nullable();
            $table->integer('a14')->nullable();
            $table->integer('a15')->nullable();
            $table->integer('a16')->nullable();
            $table->integer('a17')->nullable();
            $table->integer('a18')->nullable();
            $table->integer('a19')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cloth_defects');
    }
};
