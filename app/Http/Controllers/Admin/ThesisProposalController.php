<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use App\Models\ThesisProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ThesisProposalController extends Controller
{
    public function index()
    {
        $proposals = ThesisProposal::with('thesis.student.user')->orderBy('id', 'desc')->get();
        $theses = Thesis::with('student.user')->orderBy('id', 'desc')->get();

        return view('admin.thesis_proposals.index', compact('proposals', 'theses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'proposal_file_path' => 'nullable|string|max:255',
        ]);

        $proposal = ThesisProposal::create([
            'thesis_id' => $request->thesis_id,
            'proposal_file_path' => $request->proposal_file_path,
            'submission_date' => now(),
            'eligibility_status' => 'pending',
        ]);

        // Sync proposal_file_path in thesis if provided
        if ($request->filled('proposal_file_path')) {
            $thesis = Thesis::find($request->thesis_id);
            if ($thesis) {
                $thesis->update(['proposal_file_path' => $request->proposal_file_path]);
            }
        }

        return redirect()->back()->with('success', 'Proposal skripsi berhasil diunggah / didaftarkan.');
    }

    public function approve(Request $request, $id)
    {
        $proposal = ThesisProposal::findOrFail($id);

        $request->validate([
            'validator' => 'required|in:baak,finance,kaprodi',
            'status' => 'required|in:1,0',
            'notes' => 'nullable|string',
        ]);

        $validator = $request->validator;
        $isApproved = $request->status == '1';
        $now = $isApproved ? now() : null;

        if ($validator === 'baak') {
            $proposal->is_baak_approved = $isApproved;
            $proposal->baak_approved_at = $now;
            $proposal->baak_notes = $request->notes;
        } elseif ($validator === 'finance') {
            $proposal->is_finance_approved = $isApproved;
            $proposal->finance_approved_at = $now;
            $proposal->finance_notes = $request->notes;
        } elseif ($validator === 'kaprodi') {
            $proposal->is_kaprodi_approved = $isApproved;
            $proposal->kaprodi_approved_at = $now;
            $proposal->kaprodi_notes = $request->notes;
        }

        $proposal->checkAndUpdateEligibility();

        return redirect()->back()->with('success', 'Status kelayakan proposal (' . strtoupper($validator) . ') berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $proposal = ThesisProposal::findOrFail($id);
        $proposal->delete();

        return redirect()->back()->with('success', 'Data proposal skripsi berhasil dihapus.');
    }
}
