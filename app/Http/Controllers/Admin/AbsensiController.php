<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?: Carbon::today()->toDateString();

        $absensi = Absensi::with('user')
            ->whereDate('tanggal', $tanggal)
            ->get();

        $totalHadir = $absensi->where('status', 'Hadir')->count();
        $totalIzin = $absensi->where('status', 'Izin')->count();
        $totalSakit = $absensi->where('status', 'Sakit')->count();

        return view('pages.admin.absensi.index', compact('absensi', 'tanggal', 'totalHadir', 'totalIzin', 'totalSakit'));
    }

    public function rekap(Request $request)
    {
        $bulan = $request->bulan ?: date('m');
        $tahun = $request->tahun ?: date('Y');

        $users = User::where('role', 'pegawai')->get();
        $rekap = Absensi::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        return view('pages.admin.absensi.rekap', compact('users', 'rekap', 'bulan', 'tahun'));
    }

    public function show(Absensi $absensi)
    {
        $absensi->load('user');

        $workStartTimeSetting = \App\Models\Setting::where('key', 'work_start_time')->value('value') ?? '08:00';
        $workStartTime = Carbon::createFromFormat('H:i', $workStartTimeSetting);
        $lateThreshold = (clone $workStartTime)->addMinutes(10);

        $isLate = false;
        $minutesLate = 0;

        if ($absensi->jam_masuk) {
            $jamMasuk = Carbon::createFromFormat('H:i:s', $absensi->jam_masuk);
            if ($jamMasuk->greaterThan($lateThreshold)) {
                $isLate = true;
                $minutesLate = $jamMasuk->diffInMinutes($workStartTime);
            }
        }

        return view('pages.admin.absensi.show', compact('absensi', 'isLate', 'minutesLate', 'workStartTimeSetting'));
    }
}
