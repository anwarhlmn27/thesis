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
        // Enhance defense_revisions table for revision file uploads & 2-step approvals
        Schema::table('defense_revisions', function (Blueprint $table) {
            if (!Schema::hasColumn('defense_revisions', 'revision_file_path')) {
                $table->string('revision_file_path')->nullable()->after('description');
            }
            if (!Schema::hasColumn('defense_revisions', 'is_approved_by_examiner')) {
                $table->boolean('is_approved_by_examiner')->default(false)->after('revision_file_path');
            }
            if (!Schema::hasColumn('defense_revisions', 'examiner_approved_at')) {
                $table->timestamp('examiner_approved_at')->nullable()->after('is_approved_by_examiner');
            }
            if (!Schema::hasColumn('defense_revisions', 'is_approved_by_kaprodi')) {
                $table->boolean('is_approved_by_kaprodi')->default(false)->after('examiner_approved_at');
            }
            if (!Schema::hasColumn('defense_revisions', 'kaprodi_approved_at')) {
                $table->timestamp('kaprodi_approved_at')->nullable()->after('is_approved_by_kaprodi');
            }
        });

        // Enhance thesis_defenses table for advisor approval
        Schema::table('thesis_defenses', function (Blueprint $table) {
            if (!Schema::hasColumn('thesis_defenses', 'is_advisor_approved')) {
                $table->boolean('is_advisor_approved')->default(false)->after('status');
            }
            if (!Schema::hasColumn('thesis_defenses', 'final_file_path')) {
                $table->string('final_file_path')->nullable()->after('is_advisor_approved');
            }
        });

        // Enhance yudisiums table for official SK printing & Dean signature details
        Schema::table('yudisiums', function (Blueprint $table) {
            if (!Schema::hasColumn('yudisiums', 'dekan_name')) {
                $table->string('dekan_name')->default('Dr. H. Ahmad Dahlan, M.Pd.')->after('graduation_date');
            }
            if (!Schema::hasColumn('yudisiums', 'dekan_nip')) {
                $table->string('dekan_nip')->default('197508152002121001')->after('dekan_name');
            }
            if (!Schema::hasColumn('yudisiums', 'status')) {
                $table->enum('status', ['draft', 'approved', 'printed'])->default('draft')->after('dekan_nip');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('defense_revisions', function (Blueprint $table) {
            $table->dropColumn([
                'revision_file_path',
                'is_approved_by_examiner',
                'examiner_approved_at',
                'is_approved_by_kaprodi',
                'kaprodi_approved_at'
            ]);
        });

        Schema::table('thesis_defenses', function (Blueprint $table) {
            $table->dropColumn(['is_advisor_approved', 'final_file_path']);
        });

        Schema::table('yudisiums', function (Blueprint $table) {
            $table->dropColumn(['dekan_name', 'dekan_nip', 'status']);
        });
    }
};
