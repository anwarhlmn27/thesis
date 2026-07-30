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
        RoleUserSeeder::run();
        // 1. Seed Admin Users
        // $adminUser = User::create([
        //     'name' => 'Admin Utama',
        //     'email' => 'admin@example.com',
        //     'password' => Hash::make('password'),
        // ]);

        // // 1b. Seed Staff Users (BAAK, Finance, Library)
        // $staffData = [
        //     ['name' => 'Staf BAAK Academic', 'email' => 'baak@example.com', 'nip' => '198501012010011001', 'department' => 'BAAK', 'phone' => '081234567890'],
        //     ['name' => 'Staf Finance & Keuangan', 'email' => 'finance@example.com', 'nip' => '198602022010012002', 'department' => 'Finance', 'phone' => '081234567891'],
        //     ['name' => 'Staf Perpustakaan', 'email' => 'library@example.com', 'nip' => '198703032010011003', 'department' => 'Library', 'phone' => '081234567892'],
        // ];

        // foreach ($staffData as $s) {
        //     $user = User::create([
        //         'name' => $s['name'],
        //         'email' => $s['email'],
        //         'password' => Hash::make('password'),
        //     ]);

        //     Staff::create([
        //         'user_id' => $user->id,
        //         'nip' => $s['nip'],
        //         'department' => $s['department'],
        //         'phone' => $s['phone'],
        //     ]);
        // }

        // // 2. Seed Lecturers
        // $lecturerData = [
        //     ['name' => 'Drs. H. Ahmad Dahlan, M.T.', 'email' => 'dahlan@example.com', 'nidn' => '0412037501', 'prodi' => 'Teknik Informatika'],
        //     ['name' => 'Dr. Rina Wijaya, M.Kom.', 'email' => 'rina@example.com', 'nidn' => '0418088002', 'prodi' => 'Sistem Informasi'],
        //     ['name' => 'Prof. Budi Santoso, Ph.D.', 'email' => 'budi@example.com', 'nidn' => '0422117203', 'prodi' => 'Teknik Informatika'],
        // ];

        // $lecturers = [];
        // foreach ($lecturerData as $data) {
        //     $user = User::create([
        //         'name' => $data['name'],
        //         'email' => $data['email'],
        //         'password' => Hash::make('password'),
        //     ]);

        //     $lecturers[] = Lecturer::create([
        //         'user_id' => $user->id,
        //         'nidn' => $data['nidn'],
        //         'prodi' => $data['prodi'],
        //     ]);
        // }

        // // 3. Seed Students
        // $studentData = [
        //     ['name' => 'Ahmad Fauzi', 'email' => 'fauzi@example.com', 'nim' => '220401001', 'prodi' => 'Teknik Informatika', 'semester' => 8],
        //     ['name' => 'Siti Aminah', 'email' => 'siti@example.com', 'nim' => '220401002', 'prodi' => 'Sistem Informasi', 'semester' => 8],
        //     ['name' => 'Rian Hidayat', 'email' => 'rian@example.com', 'nim' => '200401035', 'prodi' => 'Teknik Informatika', 'semester' => 10],
        // ];

        // $students = [];
        // foreach ($studentData as $data) {
        //     $user = User::create([
        //         'name' => $data['name'],
        //         'email' => $data['email'],
        //         'password' => Hash::make('password'),
        //     ]);

        //     $students[] = Student::create([
        //         'user_id' => $user->id,
        //         'nim' => $data['nim'],
        //         'prodi' => $data['prodi'],
        //         'semester' => $data['semester'],
        //         'is_paid' => true,
        //         'is_library_clear' => true,
        //         'is_coursework_completed' => true,
        //     ]);
        // }

        // // 4. Seed Theses
        // $thesis1 = Thesis::create([
        //     'student_id' => $students[0]->id,
        //     'title' => 'Penerapan Algoritma A* Untuk Pencarian Rute Terpendek Pada Peta Kampus',
        //     'abstract' => 'Penelitian ini membahas mengenai optimasi rute terpendek di lingkungan kampus menggunakan algoritma A-Star...',
        //     'proposal_file_path' => 'uploads/proposal_fauzi.pdf',
        //     'status' => 'mentoring',
        // ]);

        // $thesis2 = Thesis::create([
        //     'student_id' => $students[1]->id,
        //     'title' => 'Analisis Keamanan Jaringan Wi-Fi Menggunakan Framework NIST',
        //     'abstract' => 'Penelitian ini berfokus pada penilaian risiko keamanan siber pada jaringan nirkabel lokal...',
        //     'proposal_file_path' => 'uploads/proposal_siti.pdf',
        //     'status' => 'proposal_seminar_scheduled',
        // ]);

        // $thesis3 = Thesis::create([
        //     'student_id' => $students[2]->id,
        //     'title' => 'Rancang Bangun Sistem Informasi Bimbingan Skripsi Online Berbasis Web',
        //     'abstract' => 'Penelitian ini mengembangkan aplikasi web untuk memfasilitasi log dan mentoring skripsi secara real-time...',
        //     'proposal_file_path' => 'uploads/proposal_rian.pdf',
        //     'final_file_path' => 'uploads/final_rian.pdf',
        //     'signed_revision_proof_path' => 'uploads/revisi_rian.pdf',
        //     'status' => 'yudisium_ready',
        // ]);

        // // 5. Seed Thesis Advisors
        // $advisor1 = ThesisAdvisor::create([
        //     'thesis_id' => $thesis1->id,
        //     'lecturer_id' => $lecturers[0]->id,
        //     'type' => 'primary',
        //     'is_approved_for_defense' => false,
        // ]);

        // $advisor2 = ThesisAdvisor::create([
        //     'thesis_id' => $thesis1->id,
        //     'lecturer_id' => $lecturers[1]->id,
        //     'type' => 'secondary',
        //     'is_approved_for_defense' => false,
        // ]);

        // $advisor3 = ThesisAdvisor::create([
        //     'thesis_id' => $thesis3->id,
        //     'lecturer_id' => $lecturers[2]->id,
        //     'type' => 'primary',
        //     'is_approved_for_defense' => true,
        //     'approved_at' => now()->subDays(5),
        // ]);

        // // 6. Seed Proposal Seminars
        // $seminar1 = ProposalSeminar::create([
        //     'thesis_id' => $thesis1->id,
        //     'seminar_date' => now()->addDays(2),
        //     'room' => 'Ruang Rapat Fakultas lantai 2',
        //     'status' => 'scheduled',
        // ]);

        // $seminar2 = ProposalSeminar::create([
        //     'thesis_id' => $thesis2->id,
        //     'seminar_date' => now()->addDays(4),
        //     'room' => 'Laboratorium Komputer Terpadu',
        //     'status' => 'scheduled',
        // ]);

        // // 7. Seed Proposal Examiners
        // ProposalExaminer::create([
        //     'proposal_seminar_id' => $seminar1->id,
        //     'lecturer_id' => $lecturers[1]->id,
        //     'position' => 'chairman',
        // ]);

        // ProposalExaminer::create([
        //     'proposal_seminar_id' => $seminar1->id,
        //     'lecturer_id' => $lecturers[2]->id,
        //     'position' => 'member',
        // ]);

        // // 8. Seed Proposal Comments
        // ProposalComment::create([
        //     'proposal_seminar_id' => $seminar1->id,
        //     'lecturer_id' => $lecturers[1]->id,
        //     'comment' => 'Kajian pustaka pada bab 2 perlu ditambahkan minimal 5 jurnal internasional terbaru.',
        // ]);

        // // 9. Seed Mentoring Logs
        // MentoringLog::create([
        //     'thesis_id' => $thesis1->id,
        //     'thesis_advisor_id' => $advisor1->id,
        //     'mentoring_date' => now()->subDays(10),
        //     'notes' => 'Diskusi perumusan masalah dan tujuan penelitian di Bab 1.',
        //     'status' => 'approved',
        //     'feedback' => 'Rumusan masalah sudah spesifik. Lanjutkan ke Bab 2 dan Bab 3.',
        // ]);

        // MentoringLog::create([
        //     'thesis_id' => $thesis1->id,
        //     'thesis_advisor_id' => $advisor1->id,
        //     'mentoring_date' => now()->subDays(3),
        //     'notes' => 'Penyerahan draf Bab 2 tentang tinjauan pustaka dan teori penunjang.',
        //     'status' => 'submitted',
        // ]);

        // // 10. Seed Thesis Defenses
        // $defense = ThesisDefense::create([
        //     'thesis_id' => $thesis3->id,
        //     'defense_date' => now()->subDays(6),
        //     'room' => 'Ruang Sidang Utama Gedung Rektorat',
        //     'status' => 'passed',
        //     'score' => 86.50,
        //     'grade' => 'A',
        // ]);

        // // 11. Seed Defense Examiners
        // DefenseExaminer::create([
        //     'thesis_defense_id' => $defense->id,
        //     'lecturer_id' => $lecturers[0]->id,
        //     'position' => 'chairman',
        //     'score' => 88.00,
        // ]);

        // DefenseExaminer::create([
        //     'thesis_defense_id' => $defense->id,
        //     'lecturer_id' => $lecturers[1]->id,
        //     'position' => 'secretary',
        //     'score' => 84.00,
        // ]);

        // DefenseExaminer::create([
        //     'thesis_defense_id' => $defense->id,
        //     'lecturer_id' => $lecturers[2]->id,
        //     'position' => 'member',
        //     'score' => 87.50,
        // ]);

        // // 12. Seed Defense Revisions
        // DefenseRevision::create([
        //     'thesis_defense_id' => $defense->id,
        //     'lecturer_id' => $lecturers[1]->id,
        //     'description' => 'Sesuaikan format daftar pustaka mengikuti APA Style Edisi ke-7.',
        //     'is_approved' => true,
        //     'approved_at' => now()->subDays(2),
        // ]);

        // // 13. Seed Yudisiums
        // Yudisium::create([
        //     'student_id' => $students[2]->id,
        //     'thesis_id' => $thesis3->id,
        //     'sk_number' => '821/SK-YUD/FT-UNIV/2026',
        //     'sk_file_path' => 'uploads/sk_yudisium_rian.pdf',
        //     'graduation_date' => now()->addMonth(),
        // ]);
    }
}

