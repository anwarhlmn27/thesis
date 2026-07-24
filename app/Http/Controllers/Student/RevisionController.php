<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use App\Models\ThesisDefense;
use App\Models\DefenseRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevisionController extends Controller
{
    public function index()
    {
        $studentId = Auth::user()->student->id;
        $thesis = Thesis::where('student_id', $studentId)->first();
        
        $defense = null;
        $revisions = [];
        if ($thesis) {
            $defense = ThesisDefense::where('thesis_id', $thesis->id)->first();
            if ($defense) {
                // Get all examiners for this defense
                $revisions = DefenseRevision::where('thesis_defense_id', $defense->id)
                    ->with('lecturer.user')
                    ->get();
            }
        }

        return view('student.revision.index', compact('thesis', 'defense', 'revisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'revision_id' => 'required|exists:defense_revisions,id',
            'revision_file' => 'required|file|max:10240',
        ]);

        $revision = DefenseRevision::findOrFail($request->revision_id);
        
        $path = $request->file('revision_file')->store('revisions', 'public');
        $revision->update([
            'file_path' => $path,
            'is_approved' => false // reset approval if they re-upload
        ]);

        return redirect()->back()->with('success', 'Dokumen revisi berhasil diunggah. Menunggu persetujuan dosen penguji.');
    }
}
