@extends('layouts.app')

@section('title', 'Data Pegawai - SIDAPEG')

@section('content')
    <div class="container-fluid">
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><i class="fas fa-users"></i> Manajemen Pegawai</h1>
                <p class="text-muted">Kelola seluruh data pegawai dan informasi kepegawaian.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('pegawai.export') }}" class="btn btn-outline-success shadow-sm">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('pegawai.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Pegawai
                </a>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="card p-4 shadow-sm border-0 mb-4" style="border-radius: 20px;">
            <form method="GET" action="{{ route('pegawai.index') }}" class="row g-3 align-items-end">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Cari Pegawai</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-0 bg-light"
                            placeholder="Nama, NIP, atau Jabatan..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Status</label>
                    <select name="status" class="form-select border-0 bg-light">
                        <option value="">Semua Status</option>
                        <option value="ASN" {{ request('status') == 'ASN' ? 'selected' : '' }}>ASN</option>
                        <option value="Non ASN" {{ request('status') == 'Non ASN' ? 'selected' : '' }}>Non ASN</option>
                    </select>
                </div>
                {{-- <div class="col-lg-3 col-md-3 col-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Unit Kerja</label>
                    <select name="unit" class="form-select border-0 bg-light">
                        <option value="">Semua Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit }}" {{ request('unit') == $unit ? 'selected' : '' }}>
                                {{ $unit }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}
            </form>
        </div>

        <!-- Data Table Card -->
        <div id="pegawai-table-container" class="card p-0 shadow-sm border-0"
            style="border-radius: 20px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-pink-light">
                        <tr>
                            <th class="ps-4" style="color: var(--pink-darker); width: 50px;">#</th>
                            <th style="color: var(--pink-darker); width: 300px;">Nama / NIP</th>
                            <th style="color: var(--pink-darker);">Jabatan & Unit</th>
                            <th style="color: var(--pink-darker); width: 120px;">Status</th>
                            <th class="text-end pe-4" style="color: var(--pink-darker); width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pegawai as $index => $p)
                            <tr>
                                <td class="ps-4 text-muted small">
                                    {{ ($pegawai->currentPage() - 1) * $pegawai->perPage() + $index + 1 }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-container me-3 shadow-sm border border-2 border-white">
                                            @if ($p->foto && file_exists(public_path('assets/img/users/' . $p->foto)))
                                                <img src="{{ asset('assets/img/users/' . $p->foto) }}"
                                                    class="w-100 h-100 object-fit-cover shadow-sm">
                                            @else
                                                <i class="fas fa-user text-muted"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $p->name }}</div>
                                            <small class="text-muted"><code
                                                    class="text-pink-darker p-0">{{ $p->nip }}</code></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-bold text-dark">{{ $p->jabatan }}</div>
                                    <div class="small text-muted">{{ $p->unit_kerja }}</div>
                                </td>
                                <td>
                                    <span
                                        class="badge {{ $p->status_pegawai == 'ASN' ? 'bg-success' : 'bg-info' }} px-3 py-1 small"
                                        style="border-radius: 6px;">
                                        {{ $p->status_pegawai }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="action-btns justify-content-end">
                                        <button type="button" class="btn btn-sm btn-edit btn-show-detail"
                                            title="Lihat Detail" style="background: #e3f2fd; color: #1976d2;"
                                            data-bs-toggle="modal" data-bs-target="#detailModal"
                                            data-name="{{ $p->name }}" data-nip="{{ $p->nip }}"
                                            data-email="{{ $p->email }}" data-jabatan="{{ $p->jabatan }}"
                                            data-unit="{{ $p->unit_kerja }}" data-status="{{ $p->status_pegawai }}"
                                            data-jk="{{ $p->jenis_kelamin }}" data-tempat_lahir="{{ $p->tempat_lahir }}"
                                            data-tanggal_lahir="{{ $p->tanggal_lahir ? $p->tanggal_lahir->format('d-m-Y') : '-' }}"
                                            data-tanggal_masuk="{{ $p->tanggal_masuk ? $p->tanggal_masuk->format('d-m-Y') : '-' }}"
                                            data-hp="{{ $p->no_hp }}" data-alamat="{{ $p->alamat }}"
                                            data-foto="{{ $p->foto && file_exists(public_path('assets/img/users/' . $p->foto)) ? asset('assets/img/users/' . $p->foto) : '' }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('pegawai.edit', $p->id) }}" class="btn btn-sm btn-edit mx-1"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('pegawai.destroy', $p->id) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus"
                                                data-name="{{ $p->name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-users fa-3x text-light mb-3"></i>
                                        <h5 class="text-muted">Tidak ada data pegawai ditemukan</h5>
                                        <p class="text-muted small">Silakan tambah data baru atau sesuaikan filter Anda.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pegawai->hasPages())
                <div class="p-4 bg-light border-top d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $pegawai->firstItem() }} - {{ $pegawai->lastItem() }} dari {{ $pegawai->total() }}
                        pegawai
                    </div>
                    <div class="pagination-container">
                        {{ $pegawai->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('pages.admin.pegawai.show')
@endsection

@push('styles')
    <style>
        .bg-pink-light {
            background-color: var(--pink-light) !important;
        }

        .text-pink-darker {
            color: var(--pink-darker) !important;
        }

        .avatar-container {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .avatar-container i {
            font-size: 20px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(30, 136, 229, 0.02);
        }

        .rounded-20 {
            border-radius: 20px !important;
        }

        /* Adjusting Bootstrap Pagination for the theme */
        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            border: none;
            color: var(--pink-darker);
            background: transparent;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--pink-dark), var(--pink-darker));
            color: white;
            box-shadow: 0 4px 10px rgba(30, 136, 229, 0.3);
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: none;
            background-color: #f1f4f9 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let searchTimer;
            const debounceDelay = 300; // milliseconds

            // Function to perform AJAX search
            function performSearch() {
                const search = $('input[name="search"]').val();
                const status = $('select[name="status"]').val();
                const unit = $('select[name="unit"]').val();

                // Show loading indicator
                $('#pegawai-table-container').css('opacity', '0.5');

                $.ajax({
                    url: '{{ route('pegawai.search') }}',
                    method: 'GET',
                    data: {
                        search: search,
                        status: status,
                        unit: unit
                    },
                    success: function(response) {
                        $('#pegawai-table-container').html(response);
                        $('#pegawai-table-container').css('opacity', '1');

                        // Re-bind delete handlers after AJAX update
                        bindDeleteHandlers();
                    },
                    error: function(xhr) {
                        console.error('Search error:', xhr);
                        $('#pegawai-table-container').css('opacity', '1');
                    }
                });
            }

            // Debounced search on input
            $('input[name="search"]').on('keyup', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(performSearch, debounceDelay);
            });

            // Immediate search on filter change
            $('select[name="status"], select[name="unit"]').on('change', function() {
                performSearch();
            });

            // Function to bind delete handlers (needed after AJAX reload)
            function bindDeleteHandlers() {
                $('.btn-delete').off('click').on('click', function(e) {
                    e.preventDefault();
                    const form = $(this).closest('form');
                    const name = $(this).data('name');

                    Swal.fire({
                        title: 'Hapus data pegawai?',
                        text: `Anda akan menghapus data "${name}" secara permanen!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#0d47a1',
                        cancelButtonColor: '#adb5bd',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#fff',
                        customClass: {
                            popup: 'rounded-20',
                            confirmButton: 'btn btn-primary px-4 py-2',
                            cancelButton: 'btn btn-secondary px-4 py-2 ms-2'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }

            // Initial bind
            bindDeleteHandlers();
        });
    </script>
@endpush
