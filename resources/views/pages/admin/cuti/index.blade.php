@extends('layouts.app')

@section('title', 'Persetujuan Cuti')

@section('content')
    <div class="page-header">
        <h1 class="fw-bold">Persetujuan Cuti</h1>
        <p class="text-muted">Proses permohonan cuti dari pegawai.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4"
            style="background: #e8f5e9; color: #2e7d32; border-radius: 12px;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Pegawai</th>
                            <th>Jenis Cuti</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuan as $cuti)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $cuti->user->name }}</div>
                                    <small class="text-muted">{{ $cuti->user->jabatan }}</small>
                                </td>
                                <td>{{ $cuti->jenis_cuti }}</td>
                                <td>
                                    <small>{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d/m/Y') }} s/d
                                        {{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match ($cuti->status) {
                                            'Pending' => 'bg-warning',
                                            'Disetujui' => 'bg-success',
                                            'Ditolak' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-3 py-1 rounded-pill small">
                                        {{ $cuti->status }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    @if ($cuti->status == 'Pending')
                                        <a href="{{ route('admin.cuti.show', $cuti->id) }}"
                                            class="btn btn-sm btn-primary rounded-pill px-3">
                                            Proses
                                        </a>
                                    @else
                                        <a href="{{ route('admin.cuti.show', $cuti->id) }}"
                                            class="btn btn-sm btn-light rounded-pill px-3">
                                            Detail
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada pengajuan cuti yang perlu
                                    diproses.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($pengajuan->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $pengajuan->links() }}
            </div>
        @endif
    </div>
@endsection
