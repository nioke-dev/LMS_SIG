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
        Schema::create('tna_submissions', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g., TNA-2024-GRS-001
            $table->string('title');
            $table->date('submission_date');
            $table->string('category');
            $table->string('urgency');
            $table->string('status'); // approved, review, rejected
            $table->text('description')->nullable();
            $table->integer('participants')->default(0);
            $table->json('participants_list')->nullable();
            $table->text('feedback')->nullable();
            $table->string('feedback_by')->nullable();
            $table->json('documents')->nullable(); // Store dummy documents as JSON for now
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tna_submissions');
    }
};
