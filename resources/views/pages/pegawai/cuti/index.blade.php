@extends('layouts.app')

@section('title', 'Daftar Pengajuan Cuti')

@section('content')
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="fw-bold">Pengajuan Cuti Saya</h1>
            <p class="text-muted">Kelola dan pantau status permohonan cuti Anda.</p>
        </div>
        <a href="{{ route('pegawai.cuti.create') }}" class="btn btn-primary shadow-sm rounded-3">
            <i class="fas fa-plus me-2"></i> Ajukan Cuti Baru
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Jenis Cuti</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuanCuti as $cuti)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $cuti->jenis_cuti }}</div>
                                    <small class="text-muted text-break"
                                        style="max-width: 200px; display: block;">{{ Str::limit($cuti->alasan, 50) }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') }}</td>
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
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('pegawai.cuti.show', $cuti->id) }}"
                                            class="btn btn-sm btn-light rounded-pill px-3">
                                            Detail
                                        </a>
                                        @if ($cuti->status == 'Pending')
                                            <form action="{{ route('pegawai.cuti.destroy', $cuti->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                    Batal
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada pengajuan cuti.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($pengajuanCuti->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $pengajuanCuti->links() }}
            </div>
        @endif
    </div>
@endsection
