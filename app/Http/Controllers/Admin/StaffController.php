<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::with('user')->orderBy('id', 'desc')->get();
        return view('admin.staff.index', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nip' => 'required|string|max:50|unique:staff',
            'department' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Staff::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'department' => $request->department,
                'phone' => $request->phone,
            ]);
        });

        return redirect()->back()->with('success', 'Data staf berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);
        $user = $staff->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . ($user ? $user->id : ''),
            'password' => 'nullable|string|min:6',
            'nip' => 'required|string|max:50|unique:staff,nip,' . $staff->id,
            'department' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request, $staff, $user) {
            if ($user) {
                $userData = [
                    'name' => $request->name,
                    'email' => $request->email,
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $user->update($userData);
            }

            $staff->update([
                'nip' => $request->nip,
                'department' => $request->department,
                'phone' => $request->phone,
            ]);
        });

        return redirect()->back()->with('success', 'Data staf berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);

        DB::transaction(function () use ($staff) {
            $user = $staff->user;
            $staff->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->back()->with('success', 'Data staf berhasil dihapus.');
    }
}
