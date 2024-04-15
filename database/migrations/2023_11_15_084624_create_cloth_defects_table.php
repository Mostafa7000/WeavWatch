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
            $table->integer('a11')->default(0)->nullable();
            $table->integer('a12')->default(0)->nullable();
            $table->integer('a13')->default(0)->nullable();
            $table->integer('a14')->default(0)->nullable();
            $table->integer('a15')->default(0)->nullable();
            $table->integer('a16')->default(0)->nullable();
            $table->integer('a17')->default(0)->nullable();
            $table->integer('a18')->default(0)->nullable();
            $table->integer('a19')->default(0)->nullable();
            $table->integer('a20')->default(0)->nullable();
            $table->integer('a21')->default(0)->nullable();
            $table->integer('a22')->default(0)->nullable();
            $table->integer('a23')->default(0)->nullable();
            $table->integer('a24')->default(0)->nullable();
            $table->integer('a25')->default(0)->nullable();
            $table->integer('a26')->default(0)->nullable();
            $table->integer('a27')->default(0)->nullable();
            $table->integer('a28')->default(0)->nullable();
            $table->integer('a29')->default(0)->nullable();
            $table->integer('a30')->default(0)->nullable();
            $table->integer('a31')->default(0)->nullable();
            $table->integer('a32')->default(0)->nullable();
            $table->integer('a33')->default(0)->nullable();
            $table->integer('a34')->default(0)->nullable();
            $table->integer('a35')->default(0)->nullable();
            $table->integer('a36')->default(0)->nullable();
            $table->integer('a37')->default(0)->nullable();
            $table->integer('a38')->default(0)->nullable();
            $table->integer('a39')->default(0)->nullable();
            $table->integer('a40')->default(0)->nullable();
            $table->string('other')->nullable();
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
