@extends('layouts.app')

@section('title', 'Absensi Pegawai')

@section('content')
    <div class="page-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <div>
            <h1 class="fw-bold">Absensi Harian</h1>
            <p class="text-muted">Silakan lakukan absen masuk dan pulang sesuai jadwal.</p>
        </div>
        <div class="current-time-card bg-white shadow-sm p-3 rounded-4 border-start border-4 border-primary">
            <div class="d-flex align-items-center gap-3">
                <div class="time-icon bg-primary-light p-2 rounded-3 text-primary">
                    <i class="fas fa-clock fa-lg"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" id="live-clock">00:00:00</h5>
                    <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Geolocation Status Banner --}}
    @if ($geoSettings['enabled'])
        <div id="geo-status" class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center gap-3"
            style="border-radius: 12px;">
            <div class="spinner-border spinner-border-sm text-info" role="status" id="geo-spinner">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div id="geo-message">Memeriksa lokasi Anda...</div>
        </div>
    @endif

    <div class="row g-4 mb-5">
        <!-- Card Absen Masuk -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="fw-bold mb-0">Absen Masuk</h5>
                        <span class="badge {{ $absensiHariIni ? 'bg-success' : 'bg-warning' }} px-3 py-2 rounded-pill">
                            {{ $absensiHariIni ? 'Sudah Masuk' : 'Belum Masuk' }}
                        </span>
                    </div>

                    @if ($absensiHariIni)
                        <div class="text-center py-4 text-success">
                            <i class="fas fa-check-circle fa-4x mb-3 animate-bounce"></i>
                            <h4 class="fw-bold mb-1">{{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') }}
                            </h4>
                            <p class="mb-0 small text-muted">Terpantau pada tanggal
                                {{ \Carbon\Carbon::parse($absensiHariIni->tanggal)->format('d/m/Y') }}</p>
                        </div>
                    @else
                        <form action="{{ route('pegawai.absensi.masuk') }}" method="POST" enctype="multipart/form-data"
                            id="form-masuk">
                            @csrf
                            {{-- <div class="mb-3">
                                <label class="form-label small fw-bold">Ambil Foto Selfie (Opsional)</label>
                                <input type="file" name="foto" class="form-control rounded-3" accept="image/*"
                                    capture="user">
                            </div> --}}
                            <input type="hidden" name="lokasi" id="lokasi-masuk">
                            <div id="btn-masuk-container" style="{{ $geoSettings['enabled'] ? 'display:none;' : '' }}">
                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 shadow-sm btn-absen">
                                    <i class="fas fa-sign-in-alt me-2"></i> Klik untuk Absen Masuk
                                </button>
                            </div>
                            @if ($geoSettings['enabled'])
                                <div id="btn-masuk-disabled" class="text-center py-3 text-muted">
                                    <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                                    <p class="mb-0 small">Menunggu verifikasi lokasi...</p>
                                </div>
                            @endif
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card Absen Pulang -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="fw-bold mb-0">Absen Pulang</h5>
                        <span
                            class="badge {{ $absensiHariIni && $absensiHariIni->jam_pulang ? 'bg-success' : 'bg-danger' }} px-3 py-2 rounded-pill">
                            {{ $absensiHariIni && $absensiHariIni->jam_pulang ? 'Sudah Pulang' : 'Belum Pulang' }}
                        </span>
                    </div>

                    @if ($absensiHariIni && $absensiHariIni->jam_pulang)
                        <div class="text-center py-4 text-primary">
                            <i class="fas fa-home fa-4x mb-3"></i>
                            <h4 class="fw-bold mb-1">{{ \Carbon\Carbon::parse($absensiHariIni->jam_pulang)->format('H:i') }}
                            </h4>
                            <p class="mb-0 small text-muted">Selesai bekerja hari ini. Terima kasih!</p>
                        </div>
                    @elseif($absensiHariIni)
                        <form action="{{ route('pegawai.absensi.pulang') }}" method="POST" enctype="multipart/form-data"
                            id="form-pulang">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Ambil Foto Selfie (Opsional)</label>
                                <input type="file" name="foto" class="form-control rounded-3" accept="image/*"
                                    capture="user">
                            </div>
                            <input type="hidden" name="lokasi" id="lokasi-pulang">
                            <div id="btn-pulang-container" style="{{ $geoSettings['enabled'] ? 'display:none;' : '' }}">
                                <button type="submit" class="btn btn-danger w-100 py-3 rounded-3 shadow-sm btn-absen">
                                    <i class="fas fa-sign-out-alt me-2"></i> Klik untuk Absen Pulang
                                </button>
                            </div>
                            @if ($geoSettings['enabled'])
                                <div id="btn-pulang-disabled" class="text-center py-3 text-muted">
                                    <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                                    <p class="mb-0 small">Menunggu verifikasi lokasi...</p>
                                </div>
                            @endif
                        </form>
                    @else
                        <div class="text-center py-4 text-muted opacity-50">
                            <i class="fas fa-lock fa-4x mb-3"></i>
                            <p class="fw-bold">Absen masuk terlebih dahulu</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 py-4 px-4">
            <h5 class="fw-bold mb-0"><i class="fas fa-history me-2 text-primary"></i> Riwayat Absensi Terakhir</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Status</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th class="text-end pe-4">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatAbsensi as $abs)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ \Carbon\Carbon::parse($abs->tanggal)->format('d M Y') }}</div>
                                    <small
                                        class="text-muted">{{ \Carbon\Carbon::parse($abs->tanggal)->isoFormat('dddd') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-success-light text-success px-3 py-1 rounded-pill small">
                                        {{ $abs->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-dark fw-medium">
                                        {{ $abs->jam_masuk ? \Carbon\Carbon::parse($abs->jam_masuk)->format('H:i') : '-' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-dark fw-medium">
                                        {{ $abs->jam_pulang ? \Carbon\Carbon::parse($abs->jam_pulang)->format('H:i') : '-' }}
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-light rounded-pill px-3" data-bs-toggle="modal"
                                        data-bs-target="#detailModal{{ $abs->id }}">
                                        Lihat
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada riwayat absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($riwayatAbsensi->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $riwayatAbsensi->links() }}
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            .bg-primary-light {
                background-color: rgba(30, 136, 229, 0.1);
            }

            .bg-success-light {
                background-color: rgba(46, 204, 113, 0.1);
            }

            .animate-bounce {
                animation: bounce 2s infinite;
            }

            @keyframes bounce {

                0%,
                20%,
                50%,
                80%,
                100% {
                    transform: translateY(0);
                }

                40% {
                    transform: translateY(-10px);
                }

                60% {
                    transform: translateY(-5px);
                }
            }

            .btn-absen {
                transition: all 0.3s;
            }

            .btn-absen:hover {
                transform: translateY(-3px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            .alert-out-of-range {
                background: #ffebee;
                color: #c62828;
            }

            .alert-in-range {
                background: #e8f5e9;
                color: #2e7d32;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Live Clock
            function updateClock() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('id-ID', {
                    hour12: false
                });
                document.getElementById('live-clock').textContent = timeString;
            }
            setInterval(updateClock, 1000);
            updateClock();

            // Geolocation Check
            const geofencingEnabled = {{ $geoSettings['enabled'] ? 'true' : 'false' }};

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const long = position.coords.longitude;
                        const locationString = lat + ',' + long;

                        // Set hidden input values
                        if (document.getElementById('lokasi-masuk')) {
                            document.getElementById('lokasi-masuk').value = locationString;
                        }
                        if (document.getElementById('lokasi-pulang')) {
                            document.getElementById('lokasi-pulang').value = locationString;
                        }

                        // Check location via API if geofencing is enabled
                        if (geofencingEnabled) {
                            fetch('{{ route('pegawai.absensi.checkLocation') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        latitude: lat,
                                        longitude: long
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    const statusEl = document.getElementById('geo-status');
                                    const messageEl = document.getElementById('geo-message');
                                    const spinnerEl = document.getElementById('geo-spinner');

                                    spinnerEl.style.display = 'none';

                                    if (data.inRange) {
                                        // Dalam jangkauan - tampilkan tombol
                                        statusEl.classList.remove('alert-info');
                                        statusEl.classList.add('alert-in-range');
                                        messageEl.innerHTML = '<i class="fas fa-check-circle me-2"></i> ' + data
                                            .message + ' (Jarak: ' + data.distance + 'm)';

                                        // Show buttons
                                        if (document.getElementById('btn-masuk-container')) {
                                            document.getElementById('btn-masuk-container').style.display = 'block';
                                            document.getElementById('btn-masuk-disabled').style.display = 'none';
                                        }
                                        if (document.getElementById('btn-pulang-container')) {
                                            document.getElementById('btn-pulang-container').style.display = 'block';
                                            document.getElementById('btn-pulang-disabled').style.display = 'none';
                                        }
                                    } else {
                                        // Di luar jangkauan - sembunyikan tombol
                                        statusEl.classList.remove('alert-info');
                                        statusEl.classList.add('alert-out-of-range');
                                        messageEl.innerHTML = '<i class="fas fa-times-circle me-2"></i> ' + data
                                        .message;

                                        // Update disabled message
                                        if (document.getElementById('btn-masuk-disabled')) {
                                            document.getElementById('btn-masuk-disabled').innerHTML =
                                                '<i class="fas fa-map-marker-alt fa-2x mb-2 text-danger"></i>' +
                                                '<p class="mb-0 small text-danger">Anda harus berada dalam radius ' +
                                                data.maxRadius + 'm dari ' + data.officeName + '</p>';
                                        }
                                        if (document.getElementById('btn-pulang-disabled')) {
                                            document.getElementById('btn-pulang-disabled').innerHTML =
                                                '<i class="fas fa-map-marker-alt fa-2x mb-2 text-danger"></i>' +
                                                '<p class="mb-0 small text-danger">Anda harus berada dalam radius ' +
                                                data.maxRadius + 'm dari ' + data.officeName + '</p>';
                                        }
                                    }
                                })
                                .catch(error => {
                                    console.error('Error checking location:', error);
                                    // Fallback: show buttons anyway
                                    if (document.getElementById('btn-masuk-container')) {
                                        document.getElementById('btn-masuk-container').style.display = 'block';
                                    }
                                    if (document.getElementById('btn-pulang-container')) {
                                        document.getElementById('btn-pulang-container').style.display = 'block';
                                    }
                                });
                        }
                    },
                    function(error) {
                        console.error('Geolocation error:', error);
                        const statusEl = document.getElementById('geo-status');
                        const messageEl = document.getElementById('geo-message');
                        const spinnerEl = document.getElementById('geo-spinner');

                        if (statusEl && messageEl && spinnerEl) {
                            spinnerEl.style.display = 'none';
                            statusEl.classList.remove('alert-info');
                            statusEl.classList.add('alert-warning');
                            messageEl.innerHTML =
                                '<i class="fas fa-exclamation-triangle me-2"></i> Tidak dapat mengakses lokasi. Mohon aktifkan GPS dan refresh halaman.';
                        }
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                // Browser tidak mendukung geolocation
                const statusEl = document.getElementById('geo-status');
                if (statusEl) {
                    statusEl.classList.remove('alert-info');
                    statusEl.classList.add('alert-warning');
                    statusEl.innerHTML =
                        '<i class="fas fa-exclamation-triangle me-2"></i> Browser Anda tidak mendukung geolokasi.';
                }
            }
        </script>
    @endpush
@endsection
