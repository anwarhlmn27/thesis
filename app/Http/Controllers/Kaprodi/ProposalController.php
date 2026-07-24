<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\ThesisProposal;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index()
    {
        // Kaprodi can see all proposals to approve them
        $proposals = ThesisProposal::with(['thesis.student.user'])->orderBy('created_at', 'desc')->get();
        return view('kaprodi.proposals.index', compact('proposals'));
    }

    public function approve(Request $request, $id)
    {
        $proposal = ThesisProposal::findOrFail($id);
        
        $request->validate([
            'is_kaprodi_approved' => 'required|boolean'
        ]);

        $proposal->update([
            'is_kaprodi_approved' => $request->is_kaprodi_approved,
        ]);

        $status = $request->is_kaprodi_approved ? 'disetujui' : 'dibatalkan persetujuannya';
        return redirect()->back()->with('success', "Proposal berhasil $status.");
    }
}
