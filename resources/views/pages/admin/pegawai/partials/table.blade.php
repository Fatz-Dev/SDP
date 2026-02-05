{{-- resources/views/pages/admin/pegawai/partials/table.blade.php --}}
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
                        <span class="badge {{ $p->status_pegawai == 'ASN' ? 'bg-success' : 'bg-info' }} px-3 py-1 small"
                            style="border-radius: 6px;">
                            {{ $p->status_pegawai }}
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="action-btns justify-content-end">
                            <button type="button" class="btn btn-sm btn-edit btn-show-detail" title="Lihat Detail"
                                style="background: #e3f2fd; color: #1976d2;" data-bs-toggle="modal"
                                data-bs-target="#detailModal" data-name="{{ $p->name }}"
                                data-nip="{{ $p->nip }}" data-email="{{ $p->email }}"
                                data-jabatan="{{ $p->jabatan }}" data-unit="{{ $p->unit_kerja }}"
                                data-status="{{ $p->status_pegawai }}" data-jk="{{ $p->jenis_kelamin }}"
                                data-tempat_lahir="{{ $p->tempat_lahir }}"
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
                            <p class="text-muted small">Silakan tambah data baru atau sesuaikan filter Anda.</p>
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
            Menampilkan {{ $pegawai->firstItem() }} - {{ $pegawai->lastItem() }} dari {{ $pegawai->total() }} pegawai
        </div>
        <div class="pagination-container">
            {{ $pegawai->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
