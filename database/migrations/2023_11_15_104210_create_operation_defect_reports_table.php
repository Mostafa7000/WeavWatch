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
        Schema::create('operation_defect_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->foreignId('dress_id')->constrained('dresses')->cascadeOnDelete();
            $table->foreignId('size_id')->constrained('sizes')->cascadeOnDelete();
            $table->foreignId('defect_id')->constrained('operation_defects')->cascadeOnDelete();
            $table->integer('08:10')->default(0)->nullable();
            $table->integer('09:10')->default(0)->nullable();
            $table->integer('10:10')->default(0)->nullable();
            $table->integer('11:10')->default(0)->nullable();
            $table->integer('12:00')->default(0)->nullable();
            $table->integer('01:30')->default(0)->nullable();
            $table->integer('02:10')->default(0)->nullable();
            $table->integer('03:10')->default(0)->nullable();
            $table->integer('04:10')->default(0)->nullable();
            $table->integer('05:10')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_defect_dress_size');
    }
};
