<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\PengajuanCuti;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengajuanCutiController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $pengajuanCuti = PengajuanCuti::where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view('pages.pegawai.cuti.index', compact('pengajuanCuti'));
    }

    public function create()
    {
        return view('pages.pegawai.cuti.create');
    }

    public function store(Request $request)
    {
        $userId = session('user_id');

        $request->validate([
            'jenis_cuti' => 'required|string|max:50',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string',
        ]);

        PengajuanCuti::create([
            'user_id' => $userId,
            'jenis_cuti' => $request->jenis_cuti,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'status' => 'Pending',
        ]);

        return redirect()->route('pegawai.cuti.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim dan sedang menunggu persetujuan.');
    }

    public function show(PengajuanCuti $cuti)
    {
        // Pastikan miliki sendiri
        if ($cuti->user_id != session('user_id')) {
            abort(403);
        }

        return view('pages.pegawai.cuti.show', compact('cuti'));
    }

    public function destroy(PengajuanCuti $cuti)
    {
        // Pastikan miliki sendiri dan masih pending
        if ($cuti->user_id != session('user_id') || $cuti->status != 'Pending') {
            abort(403);
        }

        $cuti->delete();

        return redirect()->route('pegawai.cuti.index')
            ->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }
}
