@extends('layouts.app')

@section('title', 'Monitoring Absensi')

@section('content')
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="fw-bold">Monitoring Absensi</h1>
            <p class="text-muted">Pantau kehadiran pegawai secara real-time hari ini.</p>
        </div>
        <form action="{{ route('admin.absensi.index') }}" method="GET" class="d-flex gap-2">
            <input type="date" name="tanggal" class="form-control rounded-3" value="{{ $tanggal }}"
                onchange="this.form.submit()">
        </form>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border border-black shadow-sm rounded-4 text-black">
                <div class="card-body p-4">
                    <h6 class="text-uppercase small fw-bold opacity-75">Hadir</h6>
                    <h2 class="fw-bold mb-0">{{ $totalHadir }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border border-black shadow-sm rounded-4 text-black">
                <div class="card-body p-4">
                    <h6 class="text-uppercase small fw-bold opacity-75">Izin / Sakit</h6>
                    <h2 class="fw-bold mb-0">{{ $totalIzin + $totalSakit }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border border-black shadow-sm rounded-4 text-black">
                <div class="card-body p-4">
                    <h6 class="text-uppercase small fw-bold opacity-75">Total Entri</h6>
                    <h2 class="fw-bold mb-0">{{ $absensi->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    @php
        $workStartTimeSetting = \App\Models\Setting::where('key', 'work_start_time')->value('value') ?? '08:00';
        $workStartTime = \Carbon\Carbon::createFromFormat('H:i', $workStartTimeSetting);
        $lateThreshold = (clone $workStartTime)->addMinutes(10);
    @endphp

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Pegawai</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $abs)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <img src="{{ asset('assets/img/users/' . $abs->user->foto) }}"
                                                class="w-100 h-100 object-fit-cover" alt="{{ $abs->user->name }}">
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $abs->user->name }}</div>
                                            <small class="text-muted">{{ $abs->user->nip }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $abs->jam_masuk ?: '-' }}</td>
                                <td>{{ $abs->jam_pulang ?: '-' }}</td>
                                <td>
                                    @php
                                        $isLate = false;
                                        if ($abs->jam_masuk && $abs->status == 'Hadir') {
                                            $jamMasuk = \Carbon\Carbon::createFromFormat('H:i:s', $abs->jam_masuk);
                                            if ($jamMasuk->greaterThan($lateThreshold)) {
                                                $isLate = true;
                                            }
                                        }
                                    @endphp

                                    @if ($isLate)
                                        <span class="badge bg-danger px-3 py-1 rounded-pill small">Terlambat</span>
                                    @else
                                        @php
                                            $badgeClass = match ($abs->status) {
                                                'Hadir' => 'bg-success',
                                                'Izin', 'Sakit' => 'bg-warning',
                                                'Alpa' => 'bg-danger',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span
                                            class="badge {{ $badgeClass }} px-3 py-1 rounded-pill small">{{ $abs->status }}</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.absensi.show', $abs->id) }}"
                                        class="btn btn-sm btn-light rounded-pill px-3">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Tidak ada data absensi untuk tanggal
                                    ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
