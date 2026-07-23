<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\Thesis;
use App\Models\ThesisAdvisor;
use Illuminate\Http\Request;

class ThesisAdvisorController extends Controller
{
    public function index()
    {
        $advisors = ThesisAdvisor::with(['thesis.student.user', 'lecturer.user'])->orderBy('id', 'desc')->get();
        // Theses that passed proposal seminar
        $theses = Thesis::with('student.user')->orderBy('id', 'desc')->get();
        $lecturers = Lecturer::with('user')->get();

        return view('admin.thesis_advisors.index', compact('advisors', 'theses', 'lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'type' => 'required|in:primary,secondary',
            'approved_at' => 'nullable|date',
        ]);

        ThesisAdvisor::create([
            'thesis_id' => $request->thesis_id,
            'lecturer_id' => $request->lecturer_id,
            'type' => $request->type,
            'is_approved_for_defense' => $request->has('is_approved_for_defense'),
            'approved_at' => $request->approved_at,
        ]);

        // Update thesis status to mentoring
        $thesis = Thesis::find($request->thesis_id);
        if ($thesis) {
            $thesis->update(['status' => 'mentoring']);
        }

        return redirect()->back()->with('success', 'Pembimbing Skripsi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $advisor = ThesisAdvisor::findOrFail($id);

        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'type' => 'required|in:primary,secondary',
            'approved_at' => 'nullable|date',
        ]);

        $advisor->update([
            'thesis_id' => $request->thesis_id,
            'lecturer_id' => $request->lecturer_id,
            'type' => $request->type,
            'is_approved_for_defense' => $request->has('is_approved_for_defense'),
            'approved_at' => $request->approved_at,
        ]);

        return redirect()->back()->with('success', 'Pembimbing Skripsi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $advisor = ThesisAdvisor::findOrFail($id);
        $advisor->delete();

        return redirect()->back()->with('success', 'Pembimbing Skripsi berhasil dihapus.');
    }
}
