<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class ClearanceController extends Controller
{
    public function index()
    {
        $students = Student::with('user')->get();
        return view('library.clearance.index', compact('students'));
    }

    public function updateStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update([
            'is_library_clear' => $request->has('is_library_clear')
        ]);
        return redirect()->back()->with('success', 'Status bebas perpustakaan mahasiswa berhasil diperbarui.');
    }
}
