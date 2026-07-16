<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturers = Lecturer::with('user')->orderBy('id', 'desc')->get();
        return view('admin.lecturers.index', compact('lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nidn' => 'required|string|max:50|unique:lecturers',
            'prodi' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Lecturer::create([
                'user_id' => $user->id,
                'nidn' => $request->nidn,
                'prodi' => $request->prodi,
            ]);
        });

        return redirect()->back()->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $lecturer = Lecturer::findOrFail($id);
        $user = $lecturer->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'nidn' => 'required|string|max:50|unique:lecturers,nidn,' . $lecturer->id,
            'prodi' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request, $lecturer, $user) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            $lecturer->update([
                'nidn' => $request->nidn,
                'prodi' => $request->prodi,
            ]);
        });

        return redirect()->back()->with('success', 'Dosen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lecturer = Lecturer::findOrFail($id);
        
        DB::transaction(function () use ($lecturer) {
            $user = $lecturer->user;
            $lecturer->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->back()->with('success', 'Dosen berhasil dihapus.');
    }
}
