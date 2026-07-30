<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\ProposalSeminar;
use App\Models\ProposalExaminer;
use App\Models\ThesisDefense;
use App\Models\DefenseExaminer;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExaminerController extends Controller
{
    public function index()
    {
        // Get all proposal seminars created by BAAK
        $seminars = ProposalSeminar::with(['thesis.student.user', 'proposalExaminers.lecturer.user'])
            ->orderBy('id', 'desc')
            ->get();
            
        // Get all thesis defenses created by BAAK
        $defenses = ThesisDefense::with(['thesis.student.user', 'defenseExaminers.lecturer.user'])
            ->orderBy('id', 'desc')
            ->get();
            
        $lecturers = Lecturer::with('user')->get();

        return view('kaprodi.examiners.index', compact('seminars', 'defenses', 'lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proposal_seminar_id' => 'required|exists:proposal_seminars,id',
            'lecturer_id_1' => 'required|exists:lecturers,id',
            'lecturer_id_2' => 'required|exists:lecturers,id|different:lecturer_id_1',
            'lecturer_id_3' => 'nullable|exists:lecturers,id|different:lecturer_id_1|different:lecturer_id_2',
        ]);

        DB::transaction(function () use ($request) {
            $seminar = ProposalSeminar::findOrFail($request->proposal_seminar_id);
            
            // Delete existing examiners for this seminar
            $seminar->proposalExaminers()->delete();

            // Create examiner 1 (Ketua Penguji)
            ProposalExaminer::create([
                'proposal_seminar_id' => $seminar->id,
                'lecturer_id' => $request->lecturer_id_1,
                'position' => 'chairman',
            ]);

            // Create examiner 2 (Anggota Penguji)
            ProposalExaminer::create([
                'proposal_seminar_id' => $seminar->id,
                'lecturer_id' => $request->lecturer_id_2,
                'position' => 'member',
            ]);
            
            // Create examiner 3 if provided
            if ($request->filled('lecturer_id_3')) {
                ProposalExaminer::create([
                    'proposal_seminar_id' => $seminar->id,
                    'lecturer_id' => $request->lecturer_id_3,
                    'position' => 'member',
                ]);
            }
        });

        return redirect()->back()->with('success', 'Dosen penguji seminar proposal berhasil di-plot.');
    }

    public function storeDefense(Request $request)
    {
        $request->validate([
            'thesis_defense_id' => 'required|exists:thesis_defenses,id',
            'lecturer_id_1' => 'required|exists:lecturers,id',
            'lecturer_id_2' => 'required|exists:lecturers,id|different:lecturer_id_1',
            'lecturer_id_3' => 'nullable|exists:lecturers,id|different:lecturer_id_1|different:lecturer_id_2',
        ]);

        DB::transaction(function () use ($request) {
            $defense = ThesisDefense::findOrFail($request->thesis_defense_id);
            
            // Delete existing examiners for this defense
            $defense->defenseExaminers()->delete();

            // Create examiner 1 (Ketua Penguji)
            DefenseExaminer::create([
                'thesis_defense_id' => $defense->id,
                'lecturer_id' => $request->lecturer_id_1,
                'position' => 'chairman',
            ]);

            // Create examiner 2 (Anggota Penguji)
            DefenseExaminer::create([
                'thesis_defense_id' => $defense->id,
                'lecturer_id' => $request->lecturer_id_2,
                'position' => 'member',
            ]);
            
            // Create examiner 3 if provided
            if ($request->filled('lecturer_id_3')) {
                DefenseExaminer::create([
                    'thesis_defense_id' => $defense->id,
                    'lecturer_id' => $request->lecturer_id_3,
                    'position' => 'member',
                ]);
            }
        });

        return redirect()->back()->with('success', 'Dosen penguji sidang skripsi berhasil di-plot.');
    }
}
