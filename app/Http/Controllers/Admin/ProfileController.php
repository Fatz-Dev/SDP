<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfileController extends Controller
{
    public function index()
    {
        $username = session('username');
        $user = \App\Models\User::where('username', $username)->first();

        if (!$user) {
            session()->flush();
            return redirect()->route('login')->withErrors(['error' => 'Data pengguna tidak ditemukan. Silakan login kembali.']);
        }

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

    public function exportPdf()
    {
        $username = session('username');
        $user = \App\Models\User::where('username', $username)->first();

        if (!$user) {
            return back()->withErrors(['error' => 'Data pengguna tidak ditemukan.']);
        }

        $settings = [
            'office_name' => Setting::where('key', 'office_name')->value('value') ?? 'SIDAPEG',
            'office_address' => Setting::where('key', 'office_address')->value('value') ?? 'Alamat Belum Diatur',
            'office_phone' => Setting::where('key', 'office_phone')->value('value') ?? '-',
            'office_email' => Setting::where('key', 'office_email')->value('value') ?? '-',
        ];

        $pdf = Pdf::loadView('pages.pegawai.profile.profile-pdf', compact('user', 'settings'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Biodata_' . str_replace(' ', '_', $user->name) . '_' . date('Ymd') . '.pdf');
    }
}
