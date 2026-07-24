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
}
