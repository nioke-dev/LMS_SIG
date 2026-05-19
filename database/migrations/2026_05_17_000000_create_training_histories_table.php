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
        Schema::create('training_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The SME / Instructor
            $table->string('training_name');
            $table->string('type'); // e.g., In-House Training, Workshop Operasional, Public Training, Executive Program
            $table->string('date'); // e.g., 12-14 Maret 2024
            $table->decimal('rating', 3, 1)->default(4.8); // e.g., 4.9
            $table->integer('participants_count')->default(25);
            $table->string('eval_predicate')->default('Predikat: Sangat Memuaskan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_histories');
    }
};
