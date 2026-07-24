<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use App\Models\ThesisAdvisor;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvisorController extends Controller
{
    public function index()
    {
        // Get all active theses (which means their proposal is approved)
        // Kaprodi assigns advisor AFTER proposal is approved
        $theses = Thesis::with(['student.user', 'thesisAdvisors.lecturer.user', 'latestProposal'])
            ->whereHas('latestProposal', function($q) {
                $q->where('is_kaprodi_approved', 1);
            })
            ->orderBy('id', 'desc')
            ->get();
            
        $lecturers = Lecturer::with('user')->get();

        return view('kaprodi.advisors.index', compact('theses', 'lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'lecturer_id_1' => 'required|exists:lecturers,id',
            'lecturer_id_2' => 'nullable|exists:lecturers,id|different:lecturer_id_1',
        ]);

        DB::transaction(function () use ($request) {
            $thesis = Thesis::findOrFail($request->thesis_id);
            
            // Delete existing advisors
            $thesis->thesisAdvisors()->delete();

            // Create primary advisor
            ThesisAdvisor::create([
                'thesis_id' => $thesis->id,
                'lecturer_id' => $request->lecturer_id_1,
                'type' => 'primary',
            ]);

            // Create secondary advisor if provided
            if ($request->filled('lecturer_id_2')) {
                ThesisAdvisor::create([
                    'thesis_id' => $thesis->id,
                    'lecturer_id' => $request->lecturer_id_2,
                    'type' => 'secondary',
                ]);
            }
            
            // Update thesis status to 'mentoring' if it's currently 'proposal_approved' or 'proposal_submitted'
            $thesis->update([
                'status' => 'mentoring'
            ]);
        });

        return redirect()->back()->with('success', 'Dosen pembimbing berhasil di-plot.');
    }
}
