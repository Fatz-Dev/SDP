@extends('layouts.app')

@section('title', 'Ajukan Cuti Baru')

@section('content')
    <div class="page-header">
        <h1 class="fw-bold">Ajukan Cuti Baru</h1>
        <p class="text-muted">Lengkapi formulir di bawah untuk mengajukan permohonan cuti.</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('pegawai.cuti.store') }}" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Jenis Cuti</label>
                                <select name="jenis_cuti"
                                    class="form-select rounded-3 @error('jenis_cuti') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih jenis cuti...</option>
                                    <option value="Cuti Tahunan">Cuti Tahunan</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Cuti Kelahiran">Cuti Kelahiran</option>
                                    <option value="Alasan Penting">Alasan Penting</option>
                                    <option value="Cuti Diluar Tanggungan">Cuti Diluar Tanggungan</option>
                                </select>
                                @error('jenis_cuti')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai"
                                    class="form-control rounded-3 @error('tanggal_mulai') is-invalid @enderror" required>
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai"
                                    class="form-control rounded-3 @error('tanggal_selesai') is-invalid @enderror" required>
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Alasan Cuti</label>
                            <textarea name="alasan" class="form-control rounded-3 @error('alasan') is-invalid @enderror" rows="5"
                                placeholder="Jelaskan alasan pengajuan cuti Anda secara detail..." required></textarea>
                            @error('alasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end border-top pt-4">
                            <a href="{{ route('pegawai.cuti.index') }}" class="btn btn-light rounded-3 px-4">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-3 px-4 shadow-sm">
                                Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary-light text-primary">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i> Ketentuan Cuti</h5>
                    <ul class="mb-0 small" style="list-style-type: none; padding-left: 0;">
                        <li class="mb-2"><i class="fas fa-check me-2"></i> Pengajuan dilakukan minimal 3 hari sebelum
                            tanggal mulai.</li>
                        <li class="mb-2"><i class="fas fa-check me-2"></i> Pastikan pekerjaan Anda sudah didelegasikan
                            jika perlu.</li>
                        <li class="mb-2"><i class="fas fa-check me-2"></i> Admin akan memproses pengajuan dalam 1x24 jam.
                        </li>
                        <li><i class="fas fa-check me-2"></i> Anda akan menerima notifikasi jika status diperbarui.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-primary-light {
            background-color: rgba(30, 136, 229, 0.05);
        }
    </style>
@endsection
