<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Thesis;
use App\Models\Yudisium;
use Illuminate\Http\Request;

class YudisiumController extends Controller
{
    public function index()
    {
        $yudisiums = Yudisium::with(['student.user', 'thesis'])->orderBy('id', 'desc')->get();
        $students = Student::with('user')->get();
        $theses = Thesis::with('student.user')->get();
        return view('admin.yudisiums.index', compact('yudisiums', 'students', 'theses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'thesis_id' => 'required|exists:theses,id',
            'sk_number' => 'nullable|string|max:255',
            'sk_file_path' => 'nullable|string|max:255',
            'graduation_date' => 'nullable|date',
        ]);

        Yudisium::create($request->all());

        return redirect()->back()->with('success', 'Yudisium berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $yudisium = Yudisium::findOrFail($id);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'thesis_id' => 'required|exists:theses,id',
            'sk_number' => 'nullable|string|max:255',
            'sk_file_path' => 'nullable|string|max:255',
            'graduation_date' => 'nullable|date',
        ]);

        $yudisium->update($request->all());

        return redirect()->back()->with('success', 'Yudisium berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $yudisium = Yudisium::findOrFail($id);
        $yudisium->delete();

        return redirect()->back()->with('success', 'Yudisium berhasil dihapus.');
    }
}
