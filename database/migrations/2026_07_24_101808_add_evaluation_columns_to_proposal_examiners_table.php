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
        Schema::table('proposal_examiners', function (Blueprint $table) {
            $table->enum('status', ['pending', 'passed', 'revision', 'failed'])->default('pending')->after('position');
            $table->text('notes')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposal_examiners', function (Blueprint $table) {
            $table->dropColumn(['status', 'notes']);
        });
    }
};
