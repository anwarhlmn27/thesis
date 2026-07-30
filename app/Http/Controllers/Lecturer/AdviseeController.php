<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdviseeController extends Controller
{
    /**
     * Tampilkan daftar mahasiswa bimbingan dari dosen yang sedang login.
     */
    public function index()
    {
        $lecturerId = Auth::user()->lecturer->id;

        $theses = Thesis::whereHas('thesisAdvisors', function ($query) use ($lecturerId) {
            $query->where('lecturer_id', $lecturerId);
        })->with(['student.user', 'thesisAdvisors.lecturer.user'])->get();

        return view('dosen.advisees.index', compact('theses'));
    }

    /**
     * Setujui kelayakan sidang skripsi mahasiswa (ACC Pembimbing).
     */
    public function approve($id)
    {
        $lecturerId = Auth::user()->lecturer->id;
        
        $advisor = \App\Models\ThesisAdvisor::where('thesis_id', $id)
            ->where('lecturer_id', $lecturerId)
            ->firstOrFail();

        $advisor->is_approved_for_defense = !$advisor->is_approved_for_defense;
        $advisor->approved_at = $advisor->is_approved_for_defense ? now() : null;
        $advisor->save();

        $status = $advisor->is_approved_for_defense ? 'disetujui' : 'dibatalkan persetujuannya';
        return redirect()->back()->with('success', "Kelayakan sidang mahasiswa berhasil $status.");
    }
}
