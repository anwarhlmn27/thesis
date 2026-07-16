<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\ProposalComment;
use App\Models\ProposalSeminar;
use Illuminate\Http\Request;

class ProposalCommentController extends Controller
{
    public function index()
    {
        $comments = ProposalComment::with(['proposalSeminar.thesis.student.user', 'lecturer.user'])->orderBy('id', 'desc')->get();
        $seminars = ProposalSeminar::with('thesis.student.user')->get();
        $lecturers = Lecturer::with('user')->get();
        return view('admin.proposal_comments.index', compact('comments', 'seminars', 'lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proposal_seminar_id' => 'required|exists:proposal_seminars,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'comment' => 'required|string',
        ]);

        ProposalComment::create($request->all());

        return redirect()->back()->with('success', 'Revisi & Komentar Seminar berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $commentObj = ProposalComment::findOrFail($id);

        $request->validate([
            'proposal_seminar_id' => 'required|exists:proposal_seminars,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'comment' => 'required|string',
        ]);

        $commentObj->update($request->all());

        return redirect()->back()->with('success', 'Revisi & Komentar Seminar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $commentObj = ProposalComment::findOrFail($id);
        $commentObj->delete();

        return redirect()->back()->with('success', 'Revisi & Komentar Seminar berhasil dihapus.');
    }
}
