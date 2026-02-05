@extends('layouts.app')

@section('title', 'Detail Absensi')

@section('content')
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="fw-bold">Detail Absensi</h1>
            <p class="text-muted">Informasi lengkap kehadiran pegawai.</p>
        </div>
        <a href="{{ route('admin.absensi.index', ['tanggal' => $absensi->tanggal]) }}"
            class="btn btn-light rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        {{-- Profil Singkat --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4">
                        <div class="avatar-lg text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px; font-size: 2rem;">
                            <img src="{{ asset('assets/img/users/' . $absensi->user->foto) }}"
                                class="w-100 h-100 object-fit-cover" alt="">
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">{{ $absensi->user->name }}</h3>
                            <p class="text-muted mb-2"><i class="fas fa-id-badge me-2"></i>NIP: {{ $absensi->user->nip }} |
                                <i class="fas fa-briefcase ms-2 me-2"></i>{{ $absensi->user->jabatan ?? '-' }}</p>
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-dark px-3 py-2 border rounded-pill">
                                    <i
                                        class="fas fa-calendar-day me-2 text-primary"></i>{{ \Carbon\Carbon::parse($absensi->tanggal)->isoFormat('dddd, D MMMM YYYY') }}
                                </span>
                                @if ($isLate)
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">
                                        <i class="fas fa-clock me-2"></i>Terlambat {{ $minutesLate }} Menit
                                    </span>
                                @else
                                    <span class="badge bg-success px-3 py-2 rounded-pill">
                                        <i class="fas fa-check-circle me-2"></i>Tepat Waktu
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Masuk --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold mb-0 text-success"><i class="fas fa-sign-in-alt me-2"></i> Absen Masuk</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase d-block mb-1">Waktu Masuk</label>
                        <div class="fw-bold fs-4">{{ $absensi->jam_masuk ?: 'Belum Absen' }}</div>
                        @if ($absensi->jam_masuk)
                            <small class="text-muted">Target Jam Masuk: {{ $workStartTimeSetting }} (Batas:
                                {{ \Carbon\Carbon::createFromFormat('H:i', $workStartTimeSetting)->addMinutes(10)->format('H:i') }})</small>
                        @endif
                    </div>

                    @if ($absensi->foto_masuk)
                        <div class="mb-4">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-2">Foto Selfie Masuk</label>
                            <img src="{{ asset('assets/img/absensi/' . $absensi->foto_masuk) }}"
                                class="img-fluid rounded-4 shadow-sm border"
                                style="max-height: 300px; width: 100%; object-fit: cover;">
                        </div>
                    @endif

                    @if ($absensi->lokasi_masuk)
                        <div>
                            <label class="text-muted small fw-bold text-uppercase d-block mb-2">Lokasi Masuk
                                (Koordinat)</label>
                            <div class="p-3 bg-light rounded-3 d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-danger me-3 fs-4"></i>
                                <div>
                                    <div class="fw-bold">{{ $absensi->lokasi_masuk }}</div>
                                    <a href="https://www.google.com/maps?q={{ $absensi->lokasi_masuk }}" target="_blank"
                                        class="small text-primary text-decoration-none">Lihat di Google Maps</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detail Pulang --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold mb-0 text-danger"><i class="fas fa-sign-out-alt me-2"></i> Absen Pulang</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase d-block mb-1">Waktu Pulang</label>
                        <div class="fw-bold fs-4">{{ $absensi->jam_pulang ?: 'Belum Absen' }}</div>
                    </div>

                    @if ($absensi->foto_pulang)
                        <div class="mb-4">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-2">Foto Selfie Pulang</label>
                            <img src="{{ asset('assets/img/absensi/' . $absensi->foto_pulang) }}"
                                class="img-fluid rounded-4 shadow-sm border"
                                style="max-height: 300px; width: 100%; object-fit: cover;">
                        </div>
                    @endif

                    @if ($absensi->lokasi_pulang)
                        <div>
                            <label class="text-muted small fw-bold text-uppercase d-block mb-2">Lokasi Pulang
                                (Koordinat)</label>
                            <div class="p-3 bg-light rounded-3 d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-danger me-3 fs-4"></i>
                                <div>
                                    <div class="fw-bold">{{ $absensi->lokasi_pulang }}</div>
                                    <a href="https://www.google.com/maps?q={{ $absensi->lokasi_pulang }}" target="_blank"
                                        class="small text-primary text-decoration-none">Lihat di Google Maps</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
