<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Thesis;
use App\Models\ThesisAdvisor;
use App\Models\ProposalSeminar;
use App\Models\ProposalExaminer;
use App\Models\ProposalComment;
use App\Models\MentoringLog;
use App\Models\ThesisDefense;
use App\Models\DefenseExaminer;
use App\Models\DefenseRevision;
use App\Models\Yudisium;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleUserSeeder::class);
    }
}

