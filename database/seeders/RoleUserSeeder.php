<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Staff;
use App\Models\Thesis;
use App\Models\ThesisProposal;
use App\Models\ThesisAdvisor;
use App\Models\MentoringLog;
use App\Models\ProposalSeminar;
use App\Models\ProposalExaminer;
use App\Models\ThesisDefense;
use App\Models\DefenseExaminer;
use App\Models\DefenseRevision;
use Carbon\Carbon;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = bcrypt('password');

        // Admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Administrator', 'password' => $password]
        );

        // Staff BAAK
        $baakUser = User::firstOrCreate(
            ['email' => 'baak@example.com'],
            ['name' => 'Staf BAAK', 'password' => $password]
        );
        Staff::firstOrCreate(
            ['user_id' => $baakUser->id],
            ['nip' => 'BAAK001', 'department' => 'baak']
        );

        // Staff Finance
        $financeUser = User::firstOrCreate(
            ['email' => 'finance@example.com'],
            ['name' => 'Staf Finance', 'password' => $password]
        );
        Staff::firstOrCreate(
            ['user_id' => $financeUser->id],
            ['nip' => 'FIN001', 'department' => 'finance']
        );

        // Staff Library
        $libraryUser = User::firstOrCreate(
            ['email' => 'perpus@example.com'],
            ['name' => 'Staf Perpustakaan', 'password' => $password]
        );
        Staff::firstOrCreate(
            ['user_id' => $libraryUser->id],
            ['nip' => 'LIB001', 'department' => 'library']
        );

        // Lecturer 1 (Dosen Pembimbing & Penguji Utama)
        $lecturerUser = User::firstOrCreate(
            ['email' => 'dosen@example.com'],
            ['name' => 'Dr. Hendra Wijaya, M.Kom.', 'password' => $password]
        );
        $dosen1 = Lecturer::firstOrCreate(
            ['user_id' => $lecturerUser->id],
            ['nidn' => '0412088501', 'prodi' => 'Sistem Informasi', 'is_kaprodi' => false]
        );

        // Lecturer 2 (Kaprodi)
        $kaprodiUser = User::firstOrCreate(
            ['email' => 'kaprodi@example.com'],
            ['name' => 'Dr. Ir. Budi Raharjo, M.T.', 'password' => $password]
        );
        $dosen2 = Lecturer::firstOrCreate(
            ['user_id' => $kaprodiUser->id],
            ['nidn' => '0415037902', 'prodi' => 'Informatika', 'is_kaprodi' => true]
        );

        // Lecturer 3 (Dosen Penguji Tambahan)
        $dosenUser3 = User::firstOrCreate(
            ['email' => 'dosen2@example.com'],
            ['name' => 'Dr. Ratna Sari, M.Cs.', 'password' => $password]
        );
        $dosen3 = Lecturer::firstOrCreate(
            ['user_id' => $dosenUser3->id],
            ['nidn' => '0420068803', 'prodi' => 'Sistem Informasi', 'is_kaprodi' => false]
        );

        // Students & Thesis Data
        // Student 1: Budi Santoso (Active Advisee - Bab 3, 6 logs approved)
        $u1 = User::firstOrCreate(['email' => 'mahasiswa@example.com'], ['name' => 'Budi Santoso', 'password' => $password]);
        $s1 = Student::firstOrCreate(
            ['user_id' => $u1->id],
            ['nim' => '191054101', 'prodi' => 'Sistem Informasi', 'semester' => 8]
        );       
    }
}
