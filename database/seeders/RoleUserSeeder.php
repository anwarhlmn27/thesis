<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = bcrypt('password');

        // Admin
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Administrator', 'password' => $password]
        );

        // Student
        $studentUser = \App\Models\User::firstOrCreate(
            ['email' => 'mahasiswa@example.com'],
            ['name' => 'Mahasiswa Dummy', 'password' => $password]
        );
        \App\Models\Student::firstOrCreate(
            ['user_id' => $studentUser->id],
            ['nim' => '111222333', 'prodi' => 'Sistem Informasi', 'semester' => 8]
        );

        // Lecturer
        $lecturerUser = \App\Models\User::firstOrCreate(
            ['email' => 'dosen@example.com'],
            ['name' => 'Dosen Dummy', 'password' => $password]
        );
        \App\Models\Lecturer::firstOrCreate(
            ['user_id' => $lecturerUser->id],
            ['nidn' => '999888777', 'prodi' => 'Sistem Informasi']
        );

        // Kaprodi (Hybrid Lecturer)
        $kaprodiUser = \App\Models\User::firstOrCreate(
            ['email' => 'kaprodi@example.com'],
            ['name' => 'Kaprodi Dummy', 'password' => $password]
        );
        \App\Models\Lecturer::firstOrCreate(
            ['user_id' => $kaprodiUser->id],
            ['nidn' => '999888666', 'prodi' => 'Sistem Informasi', 'is_kaprodi' => true]
        );

        // Staff BAAK
        $baakUser = \App\Models\User::firstOrCreate(
            ['email' => 'baak@example.com'],
            ['name' => 'Staf BAAK', 'password' => $password]
        );
        \App\Models\Staff::firstOrCreate(
            ['user_id' => $baakUser->id],
            ['nip' => 'BAAK001', 'department' => 'baak']
        );

        // Staff Finance
        $financeUser = \App\Models\User::firstOrCreate(
            ['email' => 'finance@example.com'],
            ['name' => 'Staf Finance', 'password' => $password]
        );
        \App\Models\Staff::firstOrCreate(
            ['user_id' => $financeUser->id],
            ['nip' => 'FIN001', 'department' => 'finance']
        );

        // Staff Library
        $libraryUser = \App\Models\User::firstOrCreate(
            ['email' => 'perpus@example.com'],
            ['name' => 'Staf Perpustakaan', 'password' => $password]
        );
        \App\Models\Staff::firstOrCreate(
            ['user_id' => $libraryUser->id],
            ['nip' => 'LIB001', 'department' => 'library']
        );
    }
}
