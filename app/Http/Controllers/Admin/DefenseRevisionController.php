<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DefenseRevision;
use App\Models\Lecturer;
use App\Models\ThesisDefense;
use App\Models\Yudisium;
use Illuminate\Http\Request;

class DefenseRevisionController extends Controller
{
    public function index()
    {
        $revisions = DefenseRevision::with(['thesisDefense.thesis.student.user', 'lecturer.user'])->orderBy('id', 'desc')->get();
        $defenses = ThesisDefense::with('thesis.student.user')->orderBy('id', 'desc')->get();
        $lecturers = Lecturer::with('user')->get();

        return view('admin.defense_revisions.index', compact('revisions', 'defenses', 'lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'thesis_defense_id' => 'required|exists:thesis_defenses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'description' => 'required|string',
            'revision_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
            'revision_file_path' => 'nullable|string|max:255',
        ]);

        $filePath = $request->revision_file_path;
        if ($request->hasFile('revision_file')) {
            $defense = ThesisDefense::with('thesis.student')->find($request->thesis_defense_id);
            $nim = ($defense && $defense->thesis && $defense->thesis->student) ? $defense->thesis->student->nim : 'unknown';
            $ext = $request->file('revision_file')->getClientOriginalExtension();
            $fileName = 'revisi_' . $nim . '.' . $ext;
            $filePath = $request->file('revision_file')->storeAs('revisions', $fileName, 'public');
        }

        DefenseRevision::create([
            'thesis_defense_id' => $request->thesis_defense_id,
            'lecturer_id' => $request->lecturer_id,
            'description' => $request->description,
            'revision_file_path' => $filePath,
            'is_approved_by_examiner' => false,
            'is_approved_by_kaprodi' => false,
            'is_approved' => false,
        ]);

        return redirect()->back()->with('success', 'Catatan Revisi Sidang berhasil ditambahkan.');
    }

    public function approve(Request $request, $id)
    {
        $revision = DefenseRevision::findOrFail($id);

        $request->validate([
            'validator' => 'required|in:examiner,kaprodi',
            'status' => 'required|in:1,0',
        ]);

        $isApproved = $request->status == '1';
        $now = $isApproved ? now() : null;

        if ($request->validator === 'examiner') {
            $revision->is_approved_by_examiner = $isApproved;
            $revision->examiner_approved_at = $now;
        } elseif ($request->validator === 'kaprodi') {
            $revision->is_approved_by_kaprodi = $isApproved;
            $revision->kaprodi_approved_at = $now;
        }

        if ($revision->is_approved_by_examiner && $revision->is_approved_by_kaprodi) {
            $revision->is_approved = true;
            $revision->approved_at = now();

            // Automatically register to Draft SK Yudisium if both approved
            $thesisDefense = $revision->thesisDefense;
            if ($thesisDefense && $thesisDefense->thesis) {
                Yudisium::firstOrCreate([
                    'student_id' => $thesisDefense->thesis->student_id,
                    'thesis_id' => $thesisDefense->thesis_id,
                ], [
                    'sk_number' => 'SK-YUD/' . date('Y') . '/' . sprintf('%04d', $thesisDefense->thesis_id),
                    'graduation_date' => now(),
                    'dekan_name' => 'Dr. H. Ahmad Dahlan, M.Pd.',
                    'dekan_nip' => '197508152002121001',
                    'status' => 'draft',
                ]);
            }
        } else {
            $revision->is_approved = false;
        }

        $revision->save();

        return redirect()->back()->with('success', 'Persetujuan revisi sidang (' . strtoupper($request->validator) . ') berhasil diperbarui.');
    }

    public function update(Request $request, $id)
    {
        $revision = DefenseRevision::findOrFail($id);

        $request->validate([
            'thesis_defense_id' => 'required|exists:thesis_defenses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'description' => 'required|string',
            'revision_file_path' => 'nullable|string|max:255',
        ]);

        $revision->update([
            'thesis_defense_id' => $request->thesis_defense_id,
            'lecturer_id' => $request->lecturer_id,
            'description' => $request->description,
            'revision_file_path' => $request->revision_file_path,
        ]);

        return redirect()->back()->with('success', 'Catatan Revisi Sidang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $revision = DefenseRevision::findOrFail($id);
        $revision->delete();

        return redirect()->back()->with('success', 'Catatan Revisi Sidang berhasil dihapus.');
    }
}
