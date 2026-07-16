<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DefenseRevision;
use App\Models\Lecturer;
use App\Models\ThesisDefense;
use Illuminate\Http\Request;

class DefenseRevisionController extends Controller
{
    public function index()
    {
        $revisions = DefenseRevision::with(['thesisDefense.thesis.student.user', 'lecturer.user'])->orderBy('id', 'desc')->get();
        $defenses = ThesisDefense::with('thesis.student.user')->get();
        $lecturers = Lecturer::with('user')->get();
        return view('admin.defense_revisions.index', compact('revisions', 'defenses', 'lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'thesis_defense_id' => 'required|exists:thesis_defenses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'description' => 'required|string',
            'approved_at' => 'nullable|date',
        ]);

        DefenseRevision::create([
            'thesis_defense_id' => $request->thesis_defense_id,
            'lecturer_id' => $request->lecturer_id,
            'description' => $request->description,
            'is_approved' => $request->has('is_approved'),
            'approved_at' => $request->approved_at,
        ]);

        return redirect()->back()->with('success', 'Revisi Sidang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $revision = DefenseRevision::findOrFail($id);

        $request->validate([
            'thesis_defense_id' => 'required|exists:thesis_defenses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'description' => 'required|string',
            'approved_at' => 'nullable|date',
        ]);

        $revision->update([
            'thesis_defense_id' => $request->thesis_defense_id,
            'lecturer_id' => $request->lecturer_id,
            'description' => $request->description,
            'is_approved' => $request->has('is_approved'),
            'approved_at' => $request->approved_at,
        ]);

        return redirect()->back()->with('success', 'Revisi Sidang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $revision = DefenseRevision::findOrFail($id);
        $revision->delete();

        return redirect()->back()->with('success', 'Revisi Sidang berhasil dihapus.');
    }
}
