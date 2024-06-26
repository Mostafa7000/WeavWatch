<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cutting_defects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->foreignId('dress_id')->constrained('dresses')->cascadeOnDelete();
            $table->foreignId('size_id')->constrained('sizes')->cascadeOnDelete();
            $table->integer('a1')->default(0)->nullable();
            $table->integer('a2')->default(0)->nullable();
            $table->integer('a3')->default(0)->nullable();
            $table->integer('a4')->default(0)->nullable();
            $table->integer('a5')->default(0)->nullable();
            $table->integer('a6')->default(0)->nullable();
            $table->integer('a7')->default(0)->nullable();
            $table->integer('a8')->default(0)->nullable();
            $table->integer('a9')->default(0)->nullable();
            $table->integer('a10')->default(0)->nullable();
            $table->string('other')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cutting_defects');
    }
};
