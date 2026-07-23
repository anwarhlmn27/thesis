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
        Schema::create('thesis_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_id')->constrained()->cascadeOnDelete();
            $table->string('proposal_file_path')->nullable();
            $table->timestamp('submission_date')->useCurrent();
            
            // Approval BAAK
            $table->boolean('is_baak_approved')->default(false);
            $table->timestamp('baak_approved_at')->nullable();
            $table->text('baak_notes')->nullable();

            // Approval Finance
            $table->boolean('is_finance_approved')->default(false);
            $table->timestamp('finance_approved_at')->nullable();
            $table->text('finance_notes')->nullable();

            // Approval Kaprodi
            $table->boolean('is_kaprodi_approved')->default(false);
            $table->timestamp('kaprodi_approved_at')->nullable();
            $table->text('kaprodi_notes')->nullable();

            // Overall status
            $table->enum('eligibility_status', ['pending', 'eligible', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thesis_proposals');
    }
};
