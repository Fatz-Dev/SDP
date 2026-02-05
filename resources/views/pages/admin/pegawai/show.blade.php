<!-- Modal Detail Pegawai -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-pink-light border-0 py-3">
                <h5 class="modal-title fw-bold text-pink-darker" id="detailModalLabel">
                    <i class="fas fa-id-card me-2"></i> Detail Pegawai
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row align-items-center mb-4">
                    <div class="col-md-4 text-center">
                        <div class="position-relative d-inline-block">
                            <div id="modal-foto-container" class="shadow-sm"
                                style="width: 150px; height: 150px; border-radius: 20px; background: var(--pink-light); display: flex; align-items: center; justify-content: center; overflow: hidden; border: 4px solid var(--white);">
                                <i id="modal-foto-icon" class="fas fa-user fa-4x text-pink-medium"></i>
                                <img id="modal-foto-img" src="" class="w-100 h-100 object-fit-cover d-none">
                            </div>
                            <span id="modal-status-badge"
                                class="badge position-absolute bottom-0 start-50 translate-middle-x mb-n2 shadow-sm px-3 py-2"
                                style="border-radius: 10px;">
                                ASN
                            </span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h3 id="modal-name" class="fw-bold text-dark mb-1">Nama Lengkap</h3>
                        <p id="modal-nip-display" class="text-muted mb-2">NIP: ---</p>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span id="modal-jabatan-display" class="badge bg-light text-dark px-3 py-2"
                                style="border-radius: 8px;">Jabatan</span>
                        </div>
                    </div>
                </div>

                <div class="row bg-light rounded-4 p-3 g-4">
                    <div class="col-md-6">
                        <div class="detail-item mb-3">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Unit Kerja</label>
                            <div id="modal-unit" class="fw-bold text-dark text-break">-</div>
                        </div>
                        <div class="detail-item mb-3">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Jenis Kelamin</label>
                            <div id="modal-jk" class="fw-bold text-dark">-</div>
                        </div>
                        <div class="detail-item mb-3">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Tempat, Tanggal
                                Lahir</label>
                            <div id="modal-ttl" class="fw-bold text-dark">-</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item mb-3">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Email</label>
                            <div id="modal-email" class="fw-bold text-dark text-break">-</div>
                        </div>
                        <div class="detail-item mb-3">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block">No. HP /
                                WhatsApp</label>
                            <div id="modal-hp" class="fw-bold text-dark">-</div>
                        </div>
                        <div class="detail-item mb-3">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Tanggal Masuk</label>
                            <div id="modal-tanggal-masuk" class="fw-bold text-dark">-</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="detail-item border-top pt-3">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Alamat</label>
                            <div id="modal-alamat" class="fw-bold text-dark text-break">-</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-secondary px-4 shadow-sm" data-bs-dismiss="modal"
                    style="border-radius: 12px;">Tutup</button>
                <a id="modal-edit-btn" href="#" class="btn btn-primary px-4 shadow-sm" style="border-radius: 12px;">
                    <i class="fas fa-edit me-2"></i> Edit Data
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            // Handle Detail Modal Population
            $('.btn-show-detail').on('click', function () {
                const btn = $(this);

                // Populate modal
                $('#modal-name').text(btn.data('name'));
                $('#modal-nip-display').text('NIP: ' + btn.data('nip'));
                $('#modal-email').text(btn.data('email') || '-');
                $('#modal-jabatan-display').text(btn.data('jabatan') || '-');
                $('#modal-unit').text(btn.data('unit') || '-');
                $('#modal-jk').text(btn.data('jk') || '-');
                $('#modal-ttl').text((btn.data('tempat_lahir') || '-') + ', ' + (btn.data('tanggal_lahir') || '-'));
                $('#modal-hp').text(btn.data('hp') || '-');
                $('#modal-alamat').text(btn.data('alamat'));
                $('#modal-tanggal-masuk').text(btn.data('tanggal_masuk') || '-');

                // Handle status badge
                const status = btn.data('status');
                const statusBadge = $('#modal-status-badge');
                if (status) {
                    statusBadge.text(status).removeClass('d-none');
                    statusBadge.removeClass('bg-success bg-info').addClass(status === 'ASN' ? 'bg-success' : 'bg-info');
                } else {
                    statusBadge.addClass('d-none');
                }

                // Handle foto
                const foto = btn.data('foto');
                const fotoImg = $('#modal-foto-img');
                const fotoIcon = $('#modal-foto-icon');
                if (foto) {
                    fotoImg.attr('src', foto).removeClass('d-none');
                    fotoIcon.addClass('d-none');
                } else {
                    fotoImg.addClass('d-none');
                    fotoIcon.removeClass('d-none');
                }

                // Link Edit - Get from the neighboring edit link
                const editUrl = btn.parent().find('a').attr('href');
                $('#modal-edit-btn').attr('href', editUrl);
            });
        });
    </script>
@endpush