<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $username = session('username');
        $user = \App\Models\User::where('username', $username)->first();

        if ($user->role === 'pegawai') {
            return view('pages.pegawai.profile.index', compact('user'));
        }

        return view('pages.admin.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $username = session('username');
        $user = \App\Models\User::where('username', $username)->first();

        if (!$user) {
            return back()->withErrors(['error' => 'User data not found.']);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        // Hanya validasi foto jika ada file yang diunggah
        if ($request->hasFile('foto')) {
            $rules['foto'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $validated = $request->validate($rules);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika bukan default
            if ($user->foto && $user->foto != 'default.png') {
                $oldFoto = public_path('assets/img/users/' . $user->foto);
                if (file_exists($oldFoto)) {
                    @unlink($oldFoto);
                }
            }

            $foto = $request->file('foto');
            $filename = $user->username . '_' . time() . '.' . $foto->getClientOriginalExtension();
            $path = public_path('assets/img/users');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $foto->move($path, $filename);
            $user->foto = $filename;
        }

        // Update other fields
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }



    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $username = session('username');
        $user = \App\Models\User::where('username', $username)->first();

        if (!$user) {
            return back()->withErrors(['error' => 'User data not found.']);
        }

        // Check if current password matches (using Hash::check because it might have been updated previously)
        if (Hash::check($request->current_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return back()->with('success', 'Password berhasil diperbarui!');
        }

        return back()->withErrors(['current_password' => 'Password saat ini salah.']);
    }
}
