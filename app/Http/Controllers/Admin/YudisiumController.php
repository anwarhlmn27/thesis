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
        $yudisiums = Yudisium::with(['students.user'])->orderBy('id', 'desc')->get();
        $students = Student::with(['user', 'theses' => function($q) {
            $q->latest();
        }])->get();

        return view('admin.yudisiums.index', compact('yudisiums', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sk_number' => 'required|string|max:255',
            'academic_year' => 'nullable|string|max:255',
            'sk_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
            'graduation_date' => 'nullable|date',
            'dekan_name' => 'nullable|string|max:255',
            'dekan_nip' => 'nullable|string|max:255',
            'status' => 'required|in:draft,approved,printed',
            'student_ids' => 'nullable|array',
            'ipk' => 'nullable|array',
            'predicate' => 'nullable|array',
        ]);

        $filePath = null;
        if ($request->hasFile('sk_file')) {
            $filePath = $request->file('sk_file')->store('yudisiums', 'public');
        }

        $yudisium = Yudisium::create([
            'sk_number' => $request->sk_number,
            'academic_year' => $request->academic_year,
            'sk_file_path' => $filePath,
            'graduation_date' => $request->graduation_date ?: now(),
            'dekan_name' => $request->dekan_name ?: 'Dr. H. Ahmad Dahlan, M.Pd.',
            'dekan_nip' => $request->dekan_nip ?: '197508152002121001',
            'status' => $request->status,
        ]);

        if ($request->student_ids) {
            $syncData = [];
            foreach ($request->student_ids as $studentId) {
                $syncData[$studentId] = [
                    'ipk' => $request->ipk[$studentId] ?? null,
                    'predicate' => $request->predicate[$studentId] ?? null,
                ];
            }
            $yudisium->students()->sync($syncData);
        }

        return redirect()->back()->with('success', 'Data Draft SK Yudisium berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $yudisium = Yudisium::findOrFail($id);

        $request->validate([
            'sk_number' => 'required|string|max:255',
            'academic_year' => 'nullable|string|max:255',
            'sk_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
            'graduation_date' => 'nullable|date',
            'dekan_name' => 'nullable|string|max:255',
            'dekan_nip' => 'nullable|string|max:255',
            'status' => 'required|in:draft,approved,printed',
            'student_ids' => 'nullable|array',
            'ipk' => 'nullable|array',
            'predicate' => 'nullable|array',
        ]);

        $data = $request->only(['sk_number', 'academic_year', 'graduation_date', 'dekan_name', 'dekan_nip', 'status']);
        
        if ($request->hasFile('sk_file')) {
            $data['sk_file_path'] = $request->file('sk_file')->store('yudisiums', 'public');
        }

        $yudisium->update($data);

        $syncData = [];
        if ($request->student_ids) {
            foreach ($request->student_ids as $studentId) {
                $syncData[$studentId] = [
                    'ipk' => $request->ipk[$studentId] ?? null,
                    'predicate' => $request->predicate[$studentId] ?? null,
                ];
            }
        }
        $yudisium->students()->sync($syncData);

        return redirect()->back()->with('success', 'Data SK Yudisium berhasil diperbarui.');
    }

    public function print($id)
    {
        $yudisium = Yudisium::with(['students.user'])->findOrFail($id);
        $yudisium->update(['status' => 'printed']);

        return view('admin.yudisiums.print', compact('yudisium'));
    }

    public function destroy($id)
    {
        $yudisium = Yudisium::findOrFail($id);
        $yudisium->students()->detach();
        $yudisium->delete();

        return redirect()->back()->with('success', 'Data SK Yudisium berhasil dihapus.');
    }
}
