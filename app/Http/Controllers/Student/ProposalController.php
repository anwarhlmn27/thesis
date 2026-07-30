<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use App\Models\ThesisProposal;
use App\Models\ProposalSeminar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProposalController extends Controller
{
    public function index()
    {
        $studentId = Auth::user()->student->id;
        $thesis = Thesis::with('latestProposal')->where('student_id', $studentId)->first();
        
        $seminar = null;
        if ($thesis) {
            $seminar = ProposalSeminar::where('thesis_id', $thesis->id)->with(['proposalExaminers.lecturer.user', 'proposalComments.lecturer.user'])->first();
        }

        return view('student.proposal.index', compact('thesis', 'seminar'));
    }

    public function store(Request $request)
    {
        $studentId = Auth::user()->student->id;
        $thesis = Thesis::where('student_id', $studentId)->first();
        
        $isFileRequired = $thesis && $thesis->latestProposal ? 'nullable' : 'required';

        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string',
            'proposal_file' => "$isFileRequired|file|mimes:pdf|max:10240",
        ]);

        if (!$thesis) {
            $thesis = Thesis::create([
                'student_id' => $studentId,
                'title' => $request->title,
                'abstract' => $request->abstract,
                'status' => 'proposal_submitted'
            ]);
        } else {
            $thesis->update([
                'title' => $request->title,
                'abstract' => $request->abstract,
            ]);
        }

        if ($request->hasFile('proposal_file')) {
            $path = $request->file('proposal_file')->store('proposals', 'public');
            
            // If exists, update or create
            ThesisProposal::updateOrCreate(
                ['thesis_id' => $thesis->id],
                ['proposal_file_path' => $path]
            );
        }

        return redirect()->back()->with('success', 'Proposal berhasil disimpan.');
    }

    public function destroy()
    {
        $studentId = Auth::user()->student->id;
        $thesis = Thesis::where('student_id', $studentId)->first();
        
        if ($thesis) {
            // Prevent deletion if a seminar has been scheduled
            if ($thesis->proposalSeminars()->exists()) {
                return redirect()->back()->with('error', 'tidak bisa menghapus proposal karena sudah di tentukan jadwal seminar proposal');
            }

            // Delete the thesis, which cascades to proposals
            $thesis->delete();
            return redirect()->back()->with('success', 'Data proposal berhasil dihapus. Anda dapat mengajukan judul baru.');
        }

        return redirect()->back()->with('error', 'Tidak ada data untuk dihapus.');
    }
}
