<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\DefenseRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevisionController extends Controller
{
    /**
     * Tampilkan daftar revisi ujian yang perlu disetujui dosen.
     */
    public function index()
    {
        $lecturerId = Auth::user()->lecturer->id;

        $revisions = DefenseRevision::where('lecturer_id', $lecturerId)
            ->with(['thesisDefense.thesis.student.user'])
            ->get();

        return view('dosen.revisions.index', compact('revisions'));
    }

    /**
     * Setujui / Tolak revisi.
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'is_approved' => 'required|boolean',
            'feedback' => 'nullable|string',
        ]);

        $lecturerId = Auth::user()->lecturer->id;

        $revision = DefenseRevision::where('lecturer_id', $lecturerId)->findOrFail($id);

        $revision->update([
            'is_approved' => $request->is_approved,
        ]);
        
        // Optional: Save feedback if we add a feedback column to DefenseRevision in the future.
        // For now, DefenseRevision model only has is_approved, file_path, etc.

        $statusText = $request->is_approved ? 'disetujui' : 'ditolak';
        return redirect()->back()->with('success', "Revisi berhasil $statusText.");
    }
}
