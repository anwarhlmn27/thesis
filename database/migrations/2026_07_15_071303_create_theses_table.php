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
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('abstract')->nullable();
            $table->string('proposal_file_path')->nullable();
            $table->string('final_file_path')->nullable();
            $table->string('signed_revision_proof_path')->nullable();
            $table->enum('status', [
                'proposal_submitted',
                'proposal_seminar_scheduled',
                'proposal_seminar_done',
                'advisor_assigned',
                'mentoring',
                'defense_registered',
                'defense_scheduled',
                'defense_done',
                'revision_period',
                'revision_approved',
                'yudisium_ready',
                'graduated'
            ])->default('proposal_submitted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theses');
    }
};
