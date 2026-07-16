<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('user')->orderBy('id', 'desc')->get();
        return view('admin.students.index', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nim' => 'required|string|max:50|unique:students',
            'prodi' => 'required|string|max:255',
            'semester' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Student::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'prodi' => $request->prodi,
                'semester' => $request->semester,
                'is_paid' => $request->has('is_paid'),
                'is_library_clear' => $request->has('is_library_clear'),
                'is_coursework_completed' => $request->has('is_coursework_completed'),
            ]);
        });

        return redirect()->back()->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'nim' => 'required|string|max:50|unique:students,nim,' . $student->id,
            'prodi' => 'required|string|max:255',
            'semester' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $student, $user) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            $student->update([
                'nim' => $request->nim,
                'prodi' => $request->prodi,
                'semester' => $request->semester,
                'is_paid' => $request->has('is_paid'),
                'is_library_clear' => $request->has('is_library_clear'),
                'is_coursework_completed' => $request->has('is_coursework_completed'),
            ]);
        });

        return redirect()->back()->with('success', 'Mahasiswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        DB::transaction(function () use ($student) {
            $user = $student->user;
            $student->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->back()->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
