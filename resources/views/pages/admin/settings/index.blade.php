@extends('layouts.app')

@section('title', 'Pengaturan Lokasi Kantor')

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-cog"></i> Pengaturan Lokasi Kantor</h1>
        <p class="text-muted">Atur koordinat dan radius untuk fitur absensi berbasis lokasi (geofencing).</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4"
            style="background: #e8f5e9; color: #2e7d32; border-radius: 12px;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4"
            style="background: #ffebee; color: #c62828; border-radius: 12px;">
            <ul class="mb-0 list-unstyled">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle me-2"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold mb-0"><i class="fas fa-map-marker-alt me-2 text-primary"></i> Lokasi Kantor</h5>
                </div>
                {{-- form pengaturan --}}
                <div class="card-body p-4">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf

                        {{-- Toggle Geofencing --}}
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="geofencing_enabled"
                                    name="settings[geofencing_enabled]" value="1"
                                    {{ $settings['geofencing_enabled'] ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="geofencing_enabled">
                                    Aktifkan Geofencing
                                </label>
                            </div>
                            <small class="text-muted">Jika diaktifkan, pegawai hanya bisa absen dalam radius lokasi
                                kantor.</small>
                        </div>

                        <hr class="my-4">

                        {{-- Nama Kantor --}}
                        <div class="mb-3">
                            <label for="office_name" class="form-label fw-bold">Nama Kantor</label>
                            <input type="text" class="form-control rounded-3" id="office_name"
                                name="settings[office_name]" value="{{ $settings['office_name'] }}"
                                placeholder="Contoh: Kantor Pusat">
                        </div>

                        {{-- Interactive Map --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-map me-2"></i> Pilih Lokasi di Peta</label>
                            <p class="text-muted small mb-2">Klik pada peta untuk memilih lokasi kantor. Lingkaran biru
                                menunjukkan radius absensi.</p>
                            <div id="map" style="height: 350px; border-radius: 12px; border: 2px solid #e0e0e0;">
                            </div>
                        </div>

                        <div class="row">
                            {{-- Latitude --}}
                            <div class="col-md-6 mb-3">
                                <label for="office_latitude" class="form-label fw-bold">Latitude</label>
                                <input type="text" class="form-control rounded-3" id="office_latitude"
                                    name="settings[office_latitude]" value="{{ $settings['office_latitude'] }}"
                                    placeholder="-6.2088" required>
                                <small class="text-muted">Contoh: -6.2088</small>
                            </div>

                            {{-- Longitude --}}
                            <div class="col-md-6 mb-3">
                                <label for="office_longitude" class="form-label fw-bold">Longitude</label>
                                <input type="text" class="form-control rounded-3" id="office_longitude"
                                    name="settings[office_longitude]" value="{{ $settings['office_longitude'] }}"
                                    placeholder="106.8456" required>
                                <small class="text-muted">Contoh: 106.8456</small>
                            </div>
                        </div>

                        {{-- Radius --}}
                        <div class="mb-4">
                            <label for="office_radius" class="form-label fw-bold">Radius (meter)</label>
                            <input type="number" class="form-control rounded-3" id="office_radius"
                                name="settings[office_radius]" value="{{ $settings['office_radius'] }}" min="10"
                                max="5000" placeholder="100" required>
                            <small class="text-muted">Jarak maksimum dari titik kantor untuk absensi (10 - 5000
                                meter).</small>
                        </div>

                        {{-- Jam Masuk --}}
                        <div class="mb-4">
                            <label for="work_start_time" class="form-label fw-bold">Jam Masuk Kantor</label>
                            <input type="time" class="form-control rounded-3" id="work_start_time"
                                name="settings[work_start_time]" value="{{ $settings['work_start_time'] }}" required>
                            <small class="text-muted">Jam mulai kerja. Pegawai akan dianggap terlambat jika absen lewat dari
                                10 menit dari jam ini.</small>
                        </div>

                        {{-- Get Current Location Button --}}
                        <div class="mb-4 d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-outline-primary rounded-3" onclick="getCurrentLocation()">
                                <i class="fas fa-crosshairs me-2"></i> Gunakan Lokasi Saat Ini
                            </button>
                            <button type="button" class="btn btn-outline-secondary rounded-3"
                                onclick="centerMapOnMarker()">
                                <i class="fas fa-sync-alt me-2"></i> Refresh Peta
                            </button>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-3 px-4">
                                <i class="fas fa-save me-2"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
                {{-- Pengaturan Saat Ini --}}
                <div class="border border-slate-950 p-4 rounded-3 mt-4 mx-4 mb-4">
                    <h6 class="fw-bold mb-3">Pengaturan Saat Ini:</h6>
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted">Status</td>
                            <td class="text-end">
                                <span class="badge {{ $settings['geofencing_enabled'] ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $settings['geofencing_enabled'] ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nama Kantor</td>
                            <td class="text-end fw-bold">{{ $settings['office_name'] }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Koordinat</td>
                            <td class="text-end"><small>{{ $settings['office_latitude'] }},
                                    {{ $settings['office_longitude'] }}</small></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Radius</td>
                            <td class="text-end fw-bold">{{ $settings['office_radius'] }} meter</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold mb-0"><i class="fas fa-info-circle me-2 text-info"></i> Informasi</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 mb-0" style="border-radius: 12px;">
                        <h6 class="fw-bold"><i class="fas fa-lightbulb me-2"></i> Tips</h6>
                        <ul class="mb-0 ps-3 small">
                            <li><strong>Klik pada peta</strong> untuk memilih lokasi kantor secara interaktif.</li>
                            <li>Lingkaran biru pada peta menunjukkan area radius absensi.</li>
                            <li>Gunakan scroll mouse untuk zoom in/out peta.</li>
                            <li>Radius yang disarankan: 50-200 meter untuk area perkantoran.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Leaflet CSS --}}
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            #map {
                z-index: 1;
            }

            .leaflet-container {
                font-family: inherit;
            }
        </style>
    @endpush

    @push('scripts')
        {{-- Leaflet JS --}}
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        <script>
            // Initialize variables
            const initialLat = {{ $settings['office_latitude'] }};
            const initialLng = {{ $settings['office_longitude'] }};
            const initialRadius = {{ $settings['office_radius'] }};

            // Initialize map
            const map = L.map('map').setView([initialLat, initialLng], 16);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Create marker
            let marker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(map);

            // Create radius circle
            let radiusCircle = L.circle([initialLat, initialLng], {
                radius: initialRadius,
                color: '#3b82f6',
                fillColor: '#3b82f6',
                fillOpacity: 0.2,
                weight: 2
            }).addTo(map);

            // Update form inputs when marker is moved
            function updateInputs(lat, lng) {
                document.getElementById('office_latitude').value = lat.toFixed(6);
                document.getElementById('office_longitude').value = lng.toFixed(6);
                radiusCircle.setLatLng([lat, lng]);
            }

            // Marker drag event
            marker.on('dragend', function(e) {
                const latlng = e.target.getLatLng();
                updateInputs(latlng.lat, latlng.lng);
            });

            // Map click event - move marker to clicked location
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });

            // Update radius circle when radius input changes
            document.getElementById('office_radius').addEventListener('input', function() {
                const newRadius = parseInt(this.value) || 100;
                radiusCircle.setRadius(newRadius);
            });

            // Update marker when latitude/longitude inputs change
            document.getElementById('office_latitude').addEventListener('change', updateMapFromInputs);
            document.getElementById('office_longitude').addEventListener('change', updateMapFromInputs);

            function updateMapFromInputs() {
                const lat = parseFloat(document.getElementById('office_latitude').value);
                const lng = parseFloat(document.getElementById('office_longitude').value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    marker.setLatLng([lat, lng]);
                    radiusCircle.setLatLng([lat, lng]);
                    map.setView([lat, lng], map.getZoom());
                }
            }

            // Center map on marker
            function centerMapOnMarker() {
                const latlng = marker.getLatLng();
                map.setView(latlng, 16);
            }

            // Get current location and update map
            function getCurrentLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            document.getElementById('office_latitude').value = lat.toFixed(6);
                            document.getElementById('office_longitude').value = lng.toFixed(6);

                            marker.setLatLng([lat, lng]);
                            radiusCircle.setLatLng([lat, lng]);
                            map.setView([lat, lng], 16);

                            // Show success toast
                            alert('Koordinat berhasil diperbarui! Jangan lupa simpan pengaturan.');
                        },
                        function(error) {
                            alert('Gagal mendapatkan lokasi: ' + error.message);
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000
                        }
                    );
                } else {
                    alert('Browser Anda tidak mendukung geolokasi.');
                }
            }
        </script>
    @endpush
@endsection
