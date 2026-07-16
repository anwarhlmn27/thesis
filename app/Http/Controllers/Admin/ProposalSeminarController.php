<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProposalSeminar;
use App\Models\Thesis;
use Illuminate\Http\Request;

class ProposalSeminarController extends Controller
{
    public function index()
    {
        $seminars = ProposalSeminar::with('thesis.student.user')->orderBy('id', 'desc')->get();
        $theses = Thesis::with('student.user')->get();
        return view('admin.proposal_seminars.index', compact('seminars', 'theses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'seminar_date' => 'nullable|date',
            'room' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,passed,failed',
        ]);

        ProposalSeminar::create($request->all());

        return redirect()->back()->with('success', 'Jadwal Seminar Proposal berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $seminar = ProposalSeminar::findOrFail($id);

        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'seminar_date' => 'nullable|date',
            'room' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,passed,failed',
        ]);

        $seminar->update($request->all());

        return redirect()->back()->with('success', 'Jadwal Seminar Proposal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $seminar = ProposalSeminar::findOrFail($id);
        $seminar->delete();

        return redirect()->back()->with('success', 'Jadwal Seminar Proposal berhasil dihapus.');
    }
}
