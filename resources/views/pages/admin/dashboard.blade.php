@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="page-header">
            <h1><i class="fas fa-tachometer-alt"></i> Panel Utama</h1>
            <div class="d-none">
                <!-- Hidden logout since layout doesn't have it in header yet, but we have it in user-info footer -->
                <form method="POST" action="{{ route('logout') }}" id="header-logout">@csrf</form>
            </div>
        </div>

        <!-- Welcome Message -->
        <div class="welcome-message mb-4">
            <div class="user-avatar-large overflow-hidden">
                @if ($user && $user->foto && file_exists(public_path('assets/img/users/' . $user->foto)))
                    <img src="{{ asset('assets/img/users/' . $user->foto) }}" class="w-100 h-100 object-fit-cover">
                @else
                    <i class="fas fa-user"></i>
                @endif
            </div>
            <h2>Selamat Datang, {{ $username }}! 👋</h2>
            <p>Anda berhasil login ke sistem AdminKu. Semua fitur telah siap untuk digunakan. Nikmati pengalaman manajemen
                yang menyenangkan!</p>
        </div>


        <div class="row g-4">
            <div class="col-md-7">
                <div class="recent-activity h-100">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h2 class="mb-0 fs-5 fw-bold text-dark"><i class="fas fa-history text-primary me-2"></i> Aktivitas
                            Terbaru</h2>
                    </div>
                    <ul class="activity-list">
                        @forelse($latestUsers as $u)
                            <li>
                                <div class="activity-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="activity-text">
                                    <h4 class="fs-6 fw-bold mb-1">Pengguna Baru Terdaftar</h4>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0 small text-muted">Pengguna "<strong>{{ $u->name }}</strong>"
                                            ({{ $u->username }}) telah dibuat</p>
                                    </div>
                                </div>
                                <div class="activity-time small text-muted">{{ $u->created_at->diffForHumans() }}</div>
                            </li>
                        @empty
                            <li class="justify-content-center py-4">
                                <p class="text-muted mb-0 small">Belum ada aktivitas terbaru.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 py-3 px-4">
                        <h5 class="fw-bold mb-0 fs-5"><i class="fas fa-clock text-success me-2"></i> Absensi Hari Ini</h5>
                        <p class="text-muted small mb-0">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($todaysAttendance as $abs)
                                <div class="list-group-item px-4 py-3 border-0 d-flex align-items-center gap-3">
                                    <div class="avatar-sm rounded-circle overflow-hidden flex-shrink-0"
                                        style="width: 45px; height: 45px;">
                                        @if ($abs->user->foto && file_exists(public_path('assets/img/users/' . $abs->user->foto)))
                                            <img src="{{ asset('assets/img/users/' . $abs->user->foto) }}"
                                                class="w-100 h-100 object-fit-cover">
                                        @else
                                            <div
                                                class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-0 text-dark">{{ $abs->user->name }}</h6>
                                        <span class="small text-muted">{{ $abs->jam_masuk ?? '-' }} -
                                            {{ $abs->jam_pulang ?? '...' }}</span>
                                    </div>
                                    <div>
                                        @php
                                            $badgeClass = match ($abs->status) {
                                                'Hadir' => 'bg-success',
                                                'Izin', 'Sakit' => 'bg-warning',
                                                'Alpa' => 'bg-danger',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span
                                            class="badge {{ $badgeClass }} rounded-pill px-3 py-1">{{ $abs->status }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-calendar-times fa-3x text-muted opacity-25"></i>
                                    </div>
                                    <p class="text-muted small mb-0">Belum ada data absensi hari ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3 text-center">
                        <a href="{{ route('admin.absensi.index') }}" class="text-decoration-none small fw-bold">Lihat Semua
                            <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer mt-5">
            <p>© 2024 Dashboard AdminKu | Dibuat dengan <i class="fas fa-heart" style="color: #ff69b4;"></i> untuk Manajemen
                yang Indah</p>
            <p id="real-time-clock">Waktu login: {{ date('d M Y, H:i:s') }}</p>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .welcome-message {
            background: linear-gradient(135deg, var(--pink-light), var(--white));
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 20px var(--shadow);
        }

        .welcome-message h2 {
            color: var(--pink-darker);
            font-size: 32px;
            margin-bottom: 10px;
        }

        .user-avatar-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(30, 136, 229, 0.3);
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            margin-bottom: 15px;
        }

        .card:nth-child(1) .card-icon {
            background: linear-gradient(135deg, #ff6b8b, #ff9999);
        }

        .card:nth-child(2) .card-icon {
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
        }

        .card:nth-child(3) .card-icon {
            background: linear-gradient(135deg, #a18cd1, #fbc2eb);
        }

        .card:nth-child(4) .card-icon {
            background: linear-gradient(135deg, #ffecd2, #fcb69f);
        }

        .number {
            font-size: 28px;
            font-weight: 700;
            color: var(--pink-darker);
            margin-bottom: 5px;
        }

        .trend {
            color: #666;
            font-size: 13px;
        }

        .recent-activity {
            background: var(--white);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 20px var(--shadow);
        }

        .recent-activity h2 {
            color: var(--pink-darker);
            margin-bottom: 20px;
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .activity-list {
            list-style: none;
            padding: 0;
        }

        .activity-list li {
            padding: 12px 0;
            border-bottom: 1px solid var(--pink-light);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .activity-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--pink-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--pink-dark);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function updateClock() {
            const timeElement = document.getElementById('real-time-clock');
            if (timeElement) {
                const now = new Date();
                timeElement.textContent =
                    `Login time: ${now.toLocaleDateString('id-ID')} ${now.toLocaleTimeString('id-id')}`;
            }
        }
        setInterval(updateClock, 1000);
    </script>
@endpush
