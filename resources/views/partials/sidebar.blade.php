<div class="sidebar">
    <div class="logo">
        <h2><i class="fas fa-heart"></i> AdminKu</h2>
        <p>Sistem Manajemen</p>
    </div>

    <ul class="nav-links">
        <li>
            <a href="{{ session('role') == 'admin' ? route('dashboard.admin') : route('dashboard.pegawai') }}"
                class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </li>

        @if (session('role') == 'admin')
            <li>
                <a href="{{ route('pegawai.index') }}" class="{{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Data Pegawai
                </a>
            </li>
            <li>
                <a href="{{ route('admin.absensi.index') }}"
                    class="{{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i> Rekap Absensi
                </a>
            </li>
            <li>
                <a href="{{ route('admin.cuti.index') }}"
                    class="{{ request()->routeIs('admin.cuti.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Persetujuan Cuti
                </a>
            </li>
            <li>
                <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Pengaturan Lokasi
                </a>
            </li>
        @else
            <li>
                <a href="{{ route('pegawai.absensi.index') }}"
                    class="{{ request()->routeIs('pegawai.absensi.*') ? 'active' : '' }}">
                    <i class="fas fa-user-check"></i> Absensi Saya
                </a>
            </li>
            <li>
                <a href="{{ route('pegawai.cuti.index') }}"
                    class="{{ request()->routeIs('pegawai.cuti.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Pengajuan Cuti
                </a>
            </li>
        @endif

        <li>
            <a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="fas fa-user-circle"></i> Profil
            </a>
        </li>
    </ul>

    @php
        $sidebar_user = \App\Models\User::where('username', session('username'))->first();
    @endphp
    <div class="user-info">
        <div class="user-avatar overflow-hidden">
            @if ($sidebar_user && $sidebar_user->foto && file_exists(public_path('assets/img/users/' . $sidebar_user->foto)))
                <img src="{{ asset('assets/img/users/' . $sidebar_user->foto) }}" class="w-100 h-100 object-fit-cover">
            @else
                <i class="fas fa-user"></i>
            @endif
        </div>
        <div class="user-details-mini">
            @if (session('admin_logged_in'))
                <h4>{{ session('username') }}</h4>
                <p>Administrator</p>
            @else
                <h4>Tamu</h4>
                <p>Pengunjung</p>
            @endif
        </div>
        <form action="{{ route('logout') }}" method="POST" id="logout-mini-form" class="d-none">
            @csrf
        </form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-mini-form').submit();"
            class="user-logout-mini" title="Keluar">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</div>
