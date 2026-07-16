<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use App\Models\ThesisDefense;
use Illuminate\Http\Request;

class ThesisDefenseController extends Controller
{
    public function index()
    {
        $defenses = ThesisDefense::with('thesis.student.user')->orderBy('id', 'desc')->get();
        $theses = Thesis::with('student.user')->get();
        return view('admin.thesis_defenses.index', compact('defenses', 'theses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'defense_date' => 'nullable|date',
            'room' => 'nullable|string|max:255',
            'status' => 'required|in:registered,scheduled,passed,failed',
            'score' => 'nullable|numeric|min:0|max:100',
            'grade' => 'nullable|string|max:5',
        ]);

        ThesisDefense::create($request->all());

        return redirect()->back()->with('success', 'Jadwal Sidang Skripsi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $defense = ThesisDefense::findOrFail($id);

        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'defense_date' => 'nullable|date',
            'room' => 'nullable|string|max:255',
            'status' => 'required|in:registered,scheduled,passed,failed',
            'score' => 'nullable|numeric|min:0|max:100',
            'grade' => 'nullable|string|max:5',
        ]);

        $defense->update($request->all());

        return redirect()->back()->with('success', 'Jadwal Sidang Skripsi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $defense = ThesisDefense::findOrFail($id);
        $defense->delete();

        return redirect()->back()->with('success', 'Jadwal Sidang Skripsi berhasil dihapus.');
    }
}
