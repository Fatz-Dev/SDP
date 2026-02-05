@extends('layouts.app')

@section('title', 'Dashboard Pegawai')

@section('content')
    <div class="page-header mb-4">
        <h1 class="fw-bold">Selamat Datang, {{ $user->name }}!</h1>
        <p class="text-muted">Senang melihat Anda kembali. Ini adalah ringkasan aktivitas Anda hari ini.</p>
    </div>

    <div class="stats-cards mb-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="card-icon bg-primary-light text-primary">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Absensi Hari Ini</h3>
                <div class="number">
                    @php
                        $absensi = \App\Models\Absensi::where('user_id', $user->id)
                            ->whereDate('tanggal', date('Y-m-d'))
                            ->first();
                    @endphp
                    {{ $absensi ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '--:--' }}
                </div>
                <p class="trend">Status: <span
                        class="badge {{ $absensi ? 'bg-success' : 'bg-warning' }} rounded-pill">{{ $absensi ? 'Hadir' : 'Belum Absen' }}</span>
                </p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="card-icon bg-purple-gradient">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>Cuti Mendatang</h3>
                <div class="number">
                    @php
                        $cuti = \App\Models\PengajuanCuti::where('user_id', $user->id)
                            ->where('status', 'Disetujui')
                            ->where('tanggal_mulai', '>', date('Y-m-d'))
                            ->first();
                    @endphp
                    {{ $cuti ? \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M') : '0' }}
                </div>
                <p class="trend">{{ $cuti ? 'Mulai ' . $cuti->jenis_cuti : 'Tidak ada cuti terjadwal' }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="card-icon bg-orange-gradient">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3>Pengajuan Pending</h3>
                <div class="number">
                    {{ \App\Models\PengajuanCuti::where('user_id', $user->id)->where('status', 'Pending')->count() }}
                </div>
                <p class="trend">Menunggu persetujuan admin</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold mb-0">Pengumuman Terbaru</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="p-4 bg-light rounded-4 mb-3">
                        <h6 class="fw-bold mb-1">Sosialisasi Aturan Absensi Baru</h6>
                        <p class="small text-muted mb-2">Diposting pada 05 Feb 2026</p>
                        <p class="mb-0">Diharapkan seluruh pegawai untuk melakukan absensi selfie menggunakan fitur
                            terbaru di sidebar demi validasi data yang lebih akurat.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-center text-center">
                    <i class="fas fa-user-check fa-4x mb-4 opacity-50"></i>
                    <h4 class="fw-bold mb-3">Siap Bekerja?</h4>
                    <p class="mb-4">Jangan lupa untuk melakukan absen masuk setiap pagi untuk mencatat kehadiran Anda.</p>
                    <a href="{{ route('pegawai.absensi.index') }}"
                        class="btn btn-light rounded-pill px-4 fw-bold text-primary shadow-sm mt-auto">Buka Menu Absensi</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-primary-light {
            background-color: rgba(30, 136, 229, 0.1);
        }
    </style>
@endsection
