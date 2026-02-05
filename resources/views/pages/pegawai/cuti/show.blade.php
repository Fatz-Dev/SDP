@extends('layouts.app')

@section('title', 'Detail Pengajuan Cuti')

@section('content')
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="fw-bold">Detail Pengajuan Cuti</h1>
            <p class="text-muted">Informasi lengkap permohonan cuti Anda.</p>
        </div>
        <a href="{{ route('pegawai.cuti.index') }}" class="btn btn-light rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4"
            style="background: #e8f5e9; color: #2e7d32; border-radius: 12px;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        {{-- Data Pengajuan --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold mb-0"><i class="fas fa-info-circle me-2 text-primary"></i> Informasi Pengajuan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Jenis Cuti</label>
                            <div class="fw-bold fs-5 text-dark">{{ $cuti->jenis_cuti }}</div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Status</label>
                            @php
                                $badgeClass = match ($cuti->status) {
                                    'Pending' => 'bg-warning',
                                    'Disetujui' => 'bg-success',
                                    'Ditolak' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">
                                {{ $cuti->status }}
                            </span>
                        </div>

                        <div class="col-12">
                            <hr class="my-2 opacity-10">
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Durasi</label>
                            <div class="fw-bold text-dark">
                                {{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($cuti->tanggal_selesai)) + 1 }}
                                Hari
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Periode Cuti</label>
                            <div class="p-3 bg-light rounded-3">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                <span class="fw-bold text-dark">
                                    {{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->isoFormat('D MMMM YYYY') }}
                                    -
                                    {{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->isoFormat('D MMMM YYYY') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Alasan Cuti</label>
                            <div class="p-3 bg-light rounded-3 text-dark">
                                {{ $cuti->alasan ?? 'Tidak ada alasan yang disertakan.' }}
                            </div>
                        </div>

                        @if ($cuti->catatan_admin)
                            <div class="col-12">
                                <label class="text-muted small fw-bold text-uppercase d-block mb-1">Tanggapan Admin</label>
                                <div
                                    class="p-3 {{ $cuti->status == 'Disetujui' ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }} rounded-3 fw-medium">
                                    <i class="fas fa-comment-dots me-2"></i>
                                    {{ $cuti->catatan_admin }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3 px-4 text-muted small">
                    <i class="fas fa-clock me-1"></i> Diajukan pada:
                    {{ $cuti->created_at->isoFormat('D MMMM YYYY, HH:mm') }}
                    @if ($cuti->status != 'Pending')
                        <span class="mx-2">|</span>
                        <i class="fas fa-check-double me-1"></i> Diproses pada:
                        {{ $cuti->updated_at->isoFormat('D MMMM YYYY, HH:mm') }}
                    @endif
                </div>
            </div>
        </div>

        {{-- Aksi --}}
        <div class="col-lg-4">
            @if ($cuti->status == 'Pending')
                <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 2rem;">
                    <div class="card-header bg-white border-0 py-4 px-4">
                        <h5 class="fw-bold mb-0"><i class="fas fa-cog me-2 text-primary"></i> Kelola Pengajuan</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">Pengajuan masih dalam status <strong>Pending</strong>. Anda dapat
                            membatalkan pengajuan ini jika diperlukan.</p>

                        <form action="{{ route('pegawai.cuti.destroy', $cuti->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100 py-3 rounded-3 shadow-sm">
                                <i class="fas fa-trash-alt me-2"></i> Batalkan Pengajuan
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            @if ($cuti->status == 'Disetujui')
                                <i class="fas fa-check-circle fa-4x text-success"></i>
                            @else
                                <i class="fas fa-times-circle fa-4x text-danger"></i>
                            @endif
                        </div>
                        <h5 class="fw-bold">Pengajuan Selesai</h5>
                        <p class="text-muted small">Cuti ini telah diproses oleh admin dengan status
                            <strong>{{ $cuti->status }}</strong>.
                        </p>
                        <hr>
                        <p class="small text-muted mb-0">Pengajuan yang sudah diproses tidak dapat dibatalkan.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
        <style>
            .bg-success-light {
                background-color: rgba(46, 204, 113, 0.1);
            }

            .bg-danger-light {
                background-color: rgba(231, 76, 60, 0.1);
            }
        </style>
    @endpush
@endsection
