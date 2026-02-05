<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            <i class="fas fa-users-cog me-2"></i>SIDAPEG
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pegawai.index') ? 'active' : '' }}"
                        href="{{ route('pegawai.index') }}">
                        <i class="fas fa-id-card me-1"></i> Pegawai
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('analytics.index') ? 'active' : '' }}"
                        href="{{ route('analytics.index') }}">
                        <i class="fas fa-chart-line me-1"></i> Analytics
                    </a>
                </li>
            </ul>

            <div class="navbar-nav align-items-center">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                        data-bs-toggle="dropdown">
                        <div class="bg-light rounded-circle text-primary d-flex align-items-center justify-content-center me-2"
                            style="width: 35px; height: 35px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="d-none d-md-inline">{{ session('username', 'Admin') }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="dropdown-header">
                                <small>Login sebagai</small>
                                <h6 class="mb-0">{{ session('username', 'Admin') }}</h6>
                                <small class="text-muted">Administrator</small>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i
                                    class="fas fa-user-circle me-2"></i> Profil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
