<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DefenseExaminer;
use App\Models\Lecturer;
use App\Models\ThesisDefense;
use Illuminate\Http\Request;

class DefenseExaminerController extends Controller
{
    public function index()
    {
        $examiners = DefenseExaminer::with(['thesisDefense.thesis.student.user', 'lecturer.user'])->orderBy('id', 'desc')->get();
        $defenses = ThesisDefense::with('thesis.student.user')->get();
        $lecturers = Lecturer::with('user')->get();
        return view('admin.defense_examiners.index', compact('examiners', 'defenses', 'lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'thesis_defense_id' => 'required|exists:thesis_defenses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'position' => 'required|in:chairman,secretary,member',
            'score' => 'nullable|numeric|min:0|max:100',
        ]);

        DefenseExaminer::create($request->all());

        return redirect()->back()->with('success', 'Penguji Sidang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $examiner = DefenseExaminer::findOrFail($id);

        $request->validate([
            'thesis_defense_id' => 'required|exists:thesis_defenses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'position' => 'required|in:chairman,secretary,member',
            'score' => 'nullable|numeric|min:0|max:100',
        ]);

        $examiner->update($request->all());

        return redirect()->back()->with('success', 'Penguji Sidang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $examiner = DefenseExaminer::findOrFail($id);
        $examiner->delete();

        return redirect()->back()->with('success', 'Penguji Sidang berhasil dihapus.');
    }
}
