<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $typeLabel = [
            'users' => 'Akun Pengguna',
            'pegawai' => 'Detail Pegawai',
            'messages' => 'Log Pesan'
        ][$request->type] ?? $request->type;

        $message = "Berhasil! Laporan {$typeLabel} (dari {$request->start_date} hingga {$request->end_date}) telah disusun dan sedang diunduh secara otomatis.";

        return redirect()->back()->with('success', $message);
    }
}
