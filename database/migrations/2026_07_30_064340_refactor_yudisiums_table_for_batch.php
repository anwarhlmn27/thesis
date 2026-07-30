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
        Schema::table('yudisiums', function (Blueprint $table) {
            if (Schema::hasColumn('yudisiums', 'student_id')) {
                $table->dropForeign(['student_id']);
                $table->dropForeign(['thesis_id']);
                $table->dropColumn(['student_id', 'thesis_id']);
            }
            if (!Schema::hasColumn('yudisiums', 'academic_year')) {
                $table->string('academic_year')->nullable()->after('sk_number');
            }
        });

        if (!Schema::hasTable('yudisium_students')) {
            Schema::create('yudisium_students', function (Blueprint $table) {
                $table->id();
                $table->foreignId('yudisium_id')->constrained('yudisiums')->cascadeOnDelete();
                $table->foreignId('student_id')->constrained()->cascadeOnDelete();
                $table->decimal('ipk', 3, 2)->nullable();
                $table->string('predicate')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yudisium_students');
        
        Schema::table('yudisiums', function (Blueprint $table) {
            $table->dropColumn('academic_year');
            $table->foreignId('student_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('thesis_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }
};
