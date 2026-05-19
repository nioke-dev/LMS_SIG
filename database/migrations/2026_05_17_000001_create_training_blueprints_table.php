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
        Schema::create('training_blueprints', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. BP-2024-001
            $table->json('tna_submission_ids')->nullable(); // Array of consolidated TNA submission IDs
            $table->string('title');
            $table->string('category');
            $table->text('objective')->nullable();
            $table->text('content')->nullable();
            $table->foreignId('sme_id')->constrained('users')->onDelete('cascade');
            $table->text('sme_instructions')->nullable();
            $table->boolean('need_workshop')->default(true);
            $table->text('workshop_note')->nullable();
            $table->date('deadline')->nullable();
            $table->string('reminder_setting')->default('H-3'); // H-3, H-2, etc.
            $table->integer('reminder_frequency')->default(1); // times per day
            $table->string('distribution')->default('internal'); // internal or public
            $table->text('rationalization')->nullable();
            $table->json('supporting_documents')->nullable(); // uploaded reference PDFs from Admin Coordinator
            $table->string('status')->default('assigned_to_sme'); // assigned_to_sme, material_submitted, approved_by_cld, revision_required
            $table->text('cld_review_notes')->nullable(); // notes from Learning Administrator (CLD Studio)
            $table->json('sme_submitted_materials')->nullable(); // uploaded PPTs/Videos from SME + metadata + description
            $table->json('sme_submitted_templates')->nullable(); // uploaded templates/installers from SME + metadata + description
            $table->text('sme_submission_notes')->nullable();
            $table->json('curriculum_structure')->nullable(); // Masterclass Curriculum JSON (Chapters, Videos, Quizzes)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_blueprints');
    }
};
