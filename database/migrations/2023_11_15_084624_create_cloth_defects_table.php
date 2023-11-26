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
            $table->integer('a1')->default(0);
            $table->integer('a2')->default(0);
            $table->integer('a3')->default(0);
            $table->integer('a4')->default(0);
            $table->integer('a5')->default(0);
            $table->integer('a6')->default(0);
            $table->integer('a7')->default(0);
            $table->integer('a8')->default(0);
            $table->integer('a9')->default(0);
            $table->integer('a10')->default(0);
            $table->integer('a11')->default(0);
            $table->integer('a12')->default(0);
            $table->integer('a13')->default(0);
            $table->integer('a14')->default(0);
            $table->integer('a15')->default(0);
            $table->integer('a16')->default(0);
            $table->integer('a17')->default(0);
            $table->integer('a18')->default(0);
            $table->integer('a19')->default(0);

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
