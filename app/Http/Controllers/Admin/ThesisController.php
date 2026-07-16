<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Thesis;
use Illuminate\Http\Request;

class ThesisController extends Controller
{
    public function index()
    {
        $theses = Thesis::with('student.user')->orderBy('id', 'desc')->get();
        $students = Student::with('user')->get();
        return view('admin.theses.index', compact('theses', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string|max:500',
            'abstract' => 'nullable|string',
            'proposal_file_path' => 'nullable|string|max:255',
            'final_file_path' => 'nullable|string|max:255',
            'signed_revision_proof_path' => 'nullable|string|max:255',
            'status' => 'required|in:proposal_submitted,proposal_seminar_scheduled,proposal_seminar_done,advisor_assigned,mentoring,defense_registered,defense_scheduled,defense_done,revision_period,revision_approved,yudisium_ready,graduated',
        ]);

        Thesis::create($request->all());

        return redirect()->back()->with('success', 'Skripsi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $thesis = Thesis::findOrFail($id);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string|max:500',
            'abstract' => 'nullable|string',
            'proposal_file_path' => 'nullable|string|max:255',
            'final_file_path' => 'nullable|string|max:255',
            'signed_revision_proof_path' => 'nullable|string|max:255',
            'status' => 'required|in:proposal_submitted,proposal_seminar_scheduled,proposal_seminar_done,advisor_assigned,mentoring,defense_registered,defense_scheduled,defense_done,revision_period,revision_approved,yudisium_ready,graduated',
        ]);

        $thesis->update($request->all());

        return redirect()->back()->with('success', 'Skripsi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $thesis = Thesis::findOrFail($id);
        $thesis->delete();

        return redirect()->back()->with('success', 'Skripsi berhasil dihapus.');
    }
}
