<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\ProposalExaminer;
use App\Models\ProposalSeminar;
use Illuminate\Http\Request;

class ProposalExaminerController extends Controller
{
    public function index()
    {
        $examiners = ProposalExaminer::with(['proposalSeminar.thesis.student.user', 'lecturer.user'])->orderBy('id', 'desc')->get();
        $seminars = ProposalSeminar::with('thesis.student.user')->get();
        $lecturers = Lecturer::with('user')->get();
        return view('admin.proposal_examiners.index', compact('examiners', 'seminars', 'lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proposal_seminar_id' => 'required|exists:proposal_seminars,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'position' => 'required|in:chairman,member',
        ]);

        ProposalExaminer::create($request->all());

        return redirect()->back()->with('success', 'Penguji Seminar berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $examiner = ProposalExaminer::findOrFail($id);

        $request->validate([
            'proposal_seminar_id' => 'required|exists:proposal_seminars,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'position' => 'required|in:chairman,member',
        ]);

        $examiner->update($request->all());

        return redirect()->back()->with('success', 'Penguji Seminar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $examiner = ProposalExaminer::findOrFail($id);
        $examiner->delete();

        return redirect()->back()->with('success', 'Penguji Seminar berhasil dihapus.');
    }
}
