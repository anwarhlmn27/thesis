<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\ProposalSeminar;
use App\Models\ThesisDefense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamScheduleController extends Controller
{
    /**
     * Tampilkan daftar ujian (Seminar & Sidang) di mana dosen menjadi penguji.
     */
    public function index()
    {
        $lecturerId = Auth::user()->lecturer->id;

        // Jadwal Seminar Proposal
        $seminars = ProposalSeminar::whereHas('proposalExaminers', function ($query) use ($lecturerId) {
            $query->where('lecturer_id', $lecturerId);
        })->with(['thesis.student.user', 'proposalExaminers'])->get();

        // Jadwal Sidang Skripsi
        $defenses = ThesisDefense::whereHas('defenseExaminers', function ($query) use ($lecturerId) {
            $query->where('lecturer_id', $lecturerId);
        })->with(['thesis.student.user', 'defenseExaminers'])->get();

        return view('dosen.exams.index', compact('seminars', 'defenses'));
    }

    public function evaluateProposal(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:passed,revision,failed',
            'notes' => 'nullable|string'
        ]);

        $lecturerId = Auth::user()->lecturer->id;
        
        // Find the examiner record
        $examiner = \App\Models\ProposalExaminer::where('proposal_seminar_id', $id)
            ->where('lecturer_id', $lecturerId)
            ->firstOrFail();

        $examiner->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Penilaian berhasil disimpan.');
    }
}
