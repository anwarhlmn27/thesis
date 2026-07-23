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
            'dekan_name' => 'nullable|string|max:255',
            'dekan_nip' => 'nullable|string|max:255',
            'status' => 'required|in:draft,approved,printed',
        ]);

        Yudisium::create([
            'student_id' => $request->student_id,
            'thesis_id' => $request->thesis_id,
            'sk_number' => $request->sk_number ?: 'SK-YUD/' . date('Y') . '/' . sprintf('%04d', $request->thesis_id),
            'sk_file_path' => $request->sk_file_path,
            'graduation_date' => $request->graduation_date ?: now(),
            'dekan_name' => $request->dekan_name ?: 'Dr. H. Ahmad Dahlan, M.Pd.',
            'dekan_nip' => $request->dekan_nip ?: '197508152002121001',
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data Draft SK Yudisium berhasil ditambahkan.');
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
            'dekan_name' => 'nullable|string|max:255',
            'dekan_nip' => 'nullable|string|max:255',
            'status' => 'required|in:draft,approved,printed',
        ]);

        $yudisium->update($request->all());

        return redirect()->back()->with('success', 'Data SK Yudisium berhasil diperbarui.');
    }

    public function print($id)
    {
        $yudisium = Yudisium::with(['student.user', 'thesis'])->findOrFail($id);
        $yudisium->update(['status' => 'printed']);

        return view('admin.yudisiums.print', compact('yudisium'));
    }

    public function destroy($id)
    {
        $yudisium = Yudisium::findOrFail($id);
        $yudisium->delete();

        return redirect()->back()->with('success', 'Data SK Yudisium berhasil dihapus.');
    }
}
