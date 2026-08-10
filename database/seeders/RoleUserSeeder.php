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
            ['nim' => '191054101', 'prodi' => 'Sistem Informasi', 'semester' => 8, 'is_paid' => true, 'is_coursework_completed' => true, 'is_library_clear' => true]
        );
        $t1 = Thesis::firstOrCreate(
            ['student_id' => $s1->id],
            [
                'title' => 'Implementasi Sistem Pakar Diagnosa Penyakit Menggunakan Metode Certainty Factor',
                'abstract' => 'Penelitian ini membangun sistem pakar berbasis web untuk membantu mendiagnosis penyakit.',
                'status' => 'mentoring',
            ]
        );
        $ta1 = ThesisAdvisor::firstOrCreate(
            ['thesis_id' => $t1->id, 'lecturer_id' => $dosen1->id],
            ['type' => 'primary', 'is_approved_for_defense' => false]
        );
        for ($i = 1; $i <= 6; $i++) {
            MentoringLog::firstOrCreate(
                ['thesis_id' => $t1->id, 'thesis_advisor_id' => $ta1->id, 'notes' => "Bimbingan Bab $i materi pembahasan dan metodologi penelitian."],
                [
                    'mentoring_date' => Carbon::now()->subDays(20 - ($i * 3)),
                    'status' => 'approved',
                    'feedback' => "Lanjutkan revisi penulisan Bab $i sesuai template.",
                ]
            );
        }

        // Student 2: Siti Aminah (Active Advisee - 10 logs, Ready for Defense / ACC Pembimbing)
        $u2 = User::firstOrCreate(['email' => 'mahasiswa2@example.com'], ['name' => 'Siti Aminah', 'password' => $password]);
        $s2 = Student::firstOrCreate(
            ['user_id' => $u2->id],
            ['nim' => '191054102', 'prodi' => 'Sistem Informasi', 'semester' => 8, 'is_paid' => true, 'is_coursework_completed' => true, 'is_library_clear' => true]
        );
        $t2 = Thesis::firstOrCreate(
            ['student_id' => $s2->id],
            [
                'title' => 'Rancang Bangun Sistem Informasi Manajemen Skripsi Berbasis Web pada Perguruan Tinggi',
                'abstract' => 'Sistem automasi alur pengajuan proposal hingga yudisium skripsi.',
                'status' => 'defense_registered',
            ]
        );
        $ta2 = ThesisAdvisor::firstOrCreate(
            ['thesis_id' => $t2->id, 'lecturer_id' => $dosen1->id],
            ['type' => 'primary', 'is_approved_for_defense' => true, 'approved_at' => Carbon::now()->subDays(2)]
        );
        for ($i = 1; $i <= 10; $i++) {
            MentoringLog::firstOrCreate(
                ['thesis_id' => $t2->id, 'thesis_advisor_id' => $ta2->id, 'notes' => "Konsultasi BAB $i dan pengujian sistem web."],
                [
                    'mentoring_date' => Carbon::now()->subDays(35 - ($i * 3)),
                    'status' => 'approved',
                    'feedback' => 'Sudah bagus dan memenuhi syarat kelayakan sidang.',
                ]
            );
        }

        // Student 3: Rizky Pratama (Advisee with 1 Pending Submitted Log for Dosen 1)
        $u3 = User::firstOrCreate(['email' => 'rizky@example.com'], ['name' => 'Rizky Pratama', 'password' => $password]);
        $s3 = Student::firstOrCreate(
            ['user_id' => $u3->id],
            ['nim' => '191054103', 'prodi' => 'Informatika', 'semester' => 8, 'is_paid' => true, 'is_coursework_completed' => true, 'is_library_clear' => false]
        );
        $t3 = Thesis::firstOrCreate(
            ['student_id' => $s3->id],
            [
                'title' => 'Analisis Sentimen Opini Publik di Twitter Menggunakan Algoritma Support Vector Machine',
                'abstract' => 'Klasifikasi opini publik terhadap kebijakan transportasi publik.',
                'status' => 'mentoring',
            ]
        );
        $ta3 = ThesisAdvisor::firstOrCreate(
            ['thesis_id' => $t3->id, 'lecturer_id' => $dosen1->id],
            ['type' => 'secondary', 'is_approved_for_defense' => false]
        );
        MentoringLog::firstOrCreate(
            ['thesis_id' => $t3->id, 'thesis_advisor_id' => $ta3->id, 'notes' => 'Diskusi Bab 1 Latar Belakang dan Rumusan Masalah'],
            ['mentoring_date' => Carbon::now()->subDays(10), 'status' => 'approved', 'feedback' => 'Perjelas motivasi riset.']
        );
        MentoringLog::firstOrCreate(
            ['thesis_id' => $t3->id, 'thesis_advisor_id' => $ta3->id, 'notes' => 'Pengajuan Bab 2 Kajian Teori dan Dataset Klasifikasi'],
            ['mentoring_date' => Carbon::now()->subDays(1), 'status' => 'submitted', 'feedback' => null]
        );

        // Student 4: Dewi Lestari (Completed / Graduated Advisee)
        $u4 = User::firstOrCreate(['email' => 'dewi@example.com'], ['name' => 'Dewi Lestari', 'password' => $password]);
        $s4 = Student::firstOrCreate(
            ['user_id' => $u4->id],
            ['nim' => '191054104', 'prodi' => 'Sistem Informasi', 'semester' => 8, 'is_paid' => true, 'is_coursework_completed' => true, 'is_library_clear' => true]
        );
        $t4 = Thesis::firstOrCreate(
            ['student_id' => $s4->id],
            [
                'title' => 'Penerapan Algoritma K-Means Clustering untuk Segmentasi Pelanggan E-Commerce',
                'abstract' => 'Segmentasi basis pelanggan untuk strategi retensi dan pemasaran terarah.',
                'status' => 'graduated',
            ]
        );
        $ta4 = ThesisAdvisor::firstOrCreate(
            ['thesis_id' => $t4->id, 'lecturer_id' => $dosen1->id],
            ['type' => 'primary', 'is_approved_for_defense' => true, 'approved_at' => Carbon::now()->subMonths(1)]
        );
        for ($i = 1; $i <= 10; $i++) {
            MentoringLog::firstOrCreate(
                ['thesis_id' => $t4->id, 'thesis_advisor_id' => $ta4->id, 'notes' => "Bimbingan Selesai Log #$i."],
                ['mentoring_date' => Carbon::now()->subDays(60 - ($i * 4)), 'status' => 'approved', 'feedback' => 'Approved.']
            );
        }

        // Student 5: Ahmad Fauzi (Proposal Seminar - Dosen 1 as Examiner Chairman)
        $u5 = User::firstOrCreate(['email' => 'ahmad@example.com'], ['name' => 'Ahmad Fauzi', 'password' => $password]);
        $s5 = Student::firstOrCreate(
            ['user_id' => $u5->id],
            ['nim' => '191054105', 'prodi' => 'Sistem Informasi', 'semester' => 8, 'is_paid' => true, 'is_coursework_completed' => true, 'is_library_clear' => true]
        );
        $t5 = Thesis::firstOrCreate(
            ['student_id' => $s5->id],
            [
                'title' => 'Sistem Pendukung Keputusan Pemilihan Dosen Berprestasi Metode AHP-TOPSIS',
                'abstract' => 'Sistem pendukung keputusan dengan multikriteria.',
                'status' => 'proposal_seminar_scheduled',
            ]
        );
        $sempro5 = ProposalSeminar::firstOrCreate(
            ['thesis_id' => $t5->id],
            [
                'seminar_date' => Carbon::now()->addDays(2)->setTime(9, 30),
                'room' => 'Ruang Seminar R.201',
                'status' => 'scheduled',
            ]
        );
        ProposalExaminer::firstOrCreate(
            ['proposal_seminar_id' => $sempro5->id, 'lecturer_id' => $dosen1->id],
            ['position' => 'chairman', 'status' => 'pending']
        );
        ProposalExaminer::firstOrCreate(
            ['proposal_seminar_id' => $sempro5->id, 'lecturer_id' => $dosen2->id],
            ['position' => 'member', 'status' => 'pending']
        );

        // Student 6: Nabila Putri (Thesis Defense - Dosen 1 as Defense Examiner)
        $u6 = User::firstOrCreate(['email' => 'nabila@example.com'], ['name' => 'Nabila Putri', 'password' => $password]);
        $s6 = Student::firstOrCreate(
            ['user_id' => $u6->id],
            ['nim' => '191054106', 'prodi' => 'Informatika', 'semester' => 8, 'is_paid' => true, 'is_coursework_completed' => true, 'is_library_clear' => true]
        );
        $t6 = Thesis::firstOrCreate(
            ['student_id' => $s6->id],
            [
                'title' => 'Implementasi Blockchain pada Keamanan dan Keabsahan Ijazah Digital',
                'abstract' => 'Smart contracts untuk verifikasi sertifikat akademik anti-pemalsuan.',
                'status' => 'defense_scheduled',
            ]
        );
        $defense6 = ThesisDefense::firstOrCreate(
            ['thesis_id' => $t6->id],
            [
                'defense_date' => Carbon::now()->addDays(4)->setTime(13, 0),
                'room' => 'Ruang Sidang Utama (Gd. Rektorat Lt.3)',
                'status' => 'scheduled',
                'is_advisor_approved' => true,
            ]
        );
        DefenseExaminer::firstOrCreate(
            ['thesis_defense_id' => $defense6->id, 'lecturer_id' => $dosen2->id],
            ['position' => 'chairman', 'score' => null]
        );
        DefenseExaminer::firstOrCreate(
            ['thesis_defense_id' => $defense6->id, 'lecturer_id' => $dosen1->id],
            ['position' => 'member', 'score' => null]
        );

        // Student 7: Eko Prasetyo (Defense Revision Pending for Dosen 1)
        $u7 = User::firstOrCreate(['email' => 'eko@example.com'], ['name' => 'Eko Prasetyo', 'password' => $password]);
        $s7 = Student::firstOrCreate(
            ['user_id' => $u7->id],
            ['nim' => '191054107', 'prodi' => 'Informatika', 'semester' => 8, 'is_paid' => true, 'is_coursework_completed' => true, 'is_library_clear' => true]
        );
        $t7 = Thesis::firstOrCreate(
            ['student_id' => $s7->id],
            [
                'title' => 'Deteksi Penyakit Daun Padi Menggunakan Arsitektur Convolutional Neural Network',
                'abstract' => 'Deep learning visual detection.',
                'status' => 'revision_period',
            ]
        );
        $defense7 = ThesisDefense::firstOrCreate(
            ['thesis_id' => $t7->id],
            [
                'defense_date' => Carbon::now()->subDays(5)->setTime(10, 0),
                'room' => 'Ruang Sidang B',
                'status' => 'passed',
                'score' => 84.5,
                'grade' => 'A',
                'is_advisor_approved' => true,
            ]
        );
        DefenseExaminer::firstOrCreate(
            ['thesis_defense_id' => $defense7->id, 'lecturer_id' => $dosen1->id],
            ['position' => 'chairman', 'score' => 85.0, 'notes' => 'Perbaiki sitasi pustaka dan diagram arsitektur BAB 3.']
        );
        DefenseRevision::firstOrCreate(
            ['thesis_defense_id' => $defense7->id, 'lecturer_id' => $dosen1->id],
            [
                'description' => 'Perbaiki format sitasi pustaka IEEE dan perjelas diagram alur arsitektur CNN pada BAB 3.',
                'revision_file_path' => 'revisions/revisi_eko_prasetyo.pdf',
                'is_approved' => false,
            ]
        );
    }
}
