@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <h1><i class="fas fa-user-circle"></i> Profil Admin</h1>
            <p class="text-muted">Kelola informasi pribadi dan pengaturan keamanan Anda.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4"
                style="background: #e8f5e9; color: #2e7d32; border-radius: 12px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4"
                style="background: #ffebee; color: #c62828; border-radius: 12px;">
                <ul class="mb-0 list-unstyled">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle me-2"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <!-- Profile Sidebar -->
            <div class="col-md-4">
                <div class="card p-4 text-center shadow-sm border-0 mb-4" style="border-radius: 20px;">
                    <div class="user-avatar-large mb-3 mx-auto position-relative overflow-hidden">
                        @if($user && $user->foto && file_exists(public_path('assets/img/users/' . $user->foto)))
                            <img src="{{ asset('assets/img/users/' . $user->foto) }}" id="avatar-preview"
                                class="w-100 h-100 object-fit-cover">
                        @else
                            <i class="fas fa-user" id="avatar-icon"></i>
                            <img src="" id="avatar-preview" class="w-100 h-100 object-fit-cover d-none">
                        @endif
                    </div>
                    <h4 class="mb-1 text-pink">{{ $user->name ?? session('username') }}</h4>
                    <p class="text-muted mb-3">{{ session('admin_logged_in') ? 'Administrator' : 'User' }}</p>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-pink mb-2"
                            onclick="document.getElementById('avatar-input').click()">
                            <i class="fas fa-camera me-2"></i> Ubah Foto
                        </button>
                    </div>

                    <hr class="my-4 opacity-10">

                    <div class="text-start">
                        <div class="mb-3 d-flex align-items-center gap-3">
                            <div class="icon-box-small bg-pink-light">
                                <i class="fas fa-envelope text-pink"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Alamat Email</small>
                                <span class="fw-bold">{{ $user->email ?? 'admin@example.com' }}</span>
                            </div>
                        </div>
                        <div class="mb-3 d-flex align-items-center gap-3">
                            <div class="icon-box-small bg-pink-light">
                                <i class="fas fa-calendar-alt text-pink"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Tanggal Bergabung</small>
                                <span
                                    class="fw-bold">{{ (isset($user) && $user->created_at) ? $user->created_at->format('d M Y') : date('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Edit Form -->
            <div class="col-md-8">
                <div class="card p-4 shadow-sm border-0 mb-4" style="border-radius: 20px;">
                    <h4 class="mb-4 text-pink"><i class="fas fa-edit"></i> Edit Informasi</h4>

                    <form action="{{ route('profile.update') }}" method="POST" id="profile-form"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="foto" id="avatar-input" class="d-none" onchange="previewAvatar(this)">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control border-pink-light"
                                    value="{{ $user->name ?? session('username') }}" style="border-radius: 10px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Alamat Email</label>
                                <input type="email" name="email" class="form-control border-pink-light"
                                    value="{{ $user->email ?? 'admin@example.com' }}" style="border-radius: 10px;">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold text-muted">Username</label>
                                <input type="text" class="form-control border-pink-light bg-light"
                                    value="{{ session('username') }}" disabled style="border-radius: 10px;">
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <a href="" class="btn btn-secondary px-4 shadow">
                                <i class="fas fa-download me-2"></i> Download PDF
                            </a>
                            <button type="submit" class="btn btn-primary px-4 shadow">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Card -->
                <!-- <div class="card p-4 shadow-sm border-0 mb-4" style="border-radius: 20px;">
                                    <h4 class="mb-4 text-pink"><i class="fas fa-lock"></i> Security Settings</h4>
                                    <form action="{{ route('profile.updatePassword') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-muted">Current Password</label>
                                                <input type="password" name="current_password" class="form-control border-pink-light"
                                                    style="border-radius: 10px;">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">New Password</label>
                                                <input type="password" name="new_password" class="form-control border-pink-light"
                                                    style="border-radius: 10px;">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Confirm New Password</label>
                                                <input type="password" name="new_password_confirmation"
                                                    class="form-control border-pink-light" style="border-radius: 10px;">
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-outline-pink px-4">
                                                Update Password
                                            </button>
                                        </div>
                                    </form>
                                </div> -->
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .user-avatar-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 50px;
            box-shadow: 0 10px 20px rgba(30, 136, 229, 0.3);
        }

        .icon-box-small {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .bg-pink-light {
            background-color: var(--pink-light);
        }

        .text-pink {
            color: var(--pink-darker);
        }

        .border-pink-light {
            border-color: var(--pink-medium);
        }

        .border-pink-light:focus {
            border-color: var(--pink-dark);
            box-shadow: 0 0 0 0.25rem rgba(255, 105, 180, 0.1);
        }

        .btn-outline-pink {
            border: 2px solid var(--pink-dark);
            color: var(--pink-dark);
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-outline-pink:hover {
            background: var(--pink-dark);
            color: white;
        }
    </style>
@endpush
@push('scripts')
    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var preview = document.getElementById('avatar-preview');
                    var icon = document.getElementById('avatar-icon');

                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    if (icon) icon.classList.add('d-none');

                    // Show reminder to save
                    Swal.fire({
                        title: 'Foto Dipilih!',
                        text: 'Klik tombol "Simpan Perubahan" untuk mengupload foto baru Anda.',
                        icon: 'info',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#1e88e5'
                    });
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush