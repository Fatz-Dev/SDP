<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanCuti;
use Illuminate\Http\Request;

class PengajuanCutiController extends Controller
{
    public function index()
    {
        $pengajuan = PengajuanCuti::with('user')
            ->latest()
            ->paginate(15);

        return view('pages.admin.cuti.index', compact('pengajuan'));
    }

    public function show(PengajuanCuti $cuti)
    {
        $cuti->load('user');
        return view('pages.admin.cuti.show', compact('cuti'));
    }

    public function updateStatus(Request $request, PengajuanCuti $cuti)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $cuti->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('success', 'Status pengajuan cuti berhasil diperbarui.');
    }
}
