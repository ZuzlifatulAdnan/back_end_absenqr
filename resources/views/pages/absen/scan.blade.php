@extends('layouts.app')

@section('title', 'Scan QR Absen')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Scan QR Absen</h1>
            </div>

            <div class="row mt-4">
                <!-- Kolom Kiri: Kamera Scan -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Scan QR Absen</h4>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <div class="mb-3">
                                <div id="reader" style="width: 100%; height: auto;"></div>
                            </div>

                            <form method="POST" action="{{ route('absen.scan') }}" id="absenForm">
                                @csrf
                                <input type="hidden" name="token_qr" id="token_qr" required>
                                <input type="hidden" name="lat" id="lat" required>
                                <input type="hidden" name="long" id="long" required>

                                <button type="submit" class="btn btn-primary mt-3" id="submitBtn" disabled>Absen Sekarang</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Peta Lokasi -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Lokasi Kelas & Radius Absen</h4>
                        </div>
                        <div class="card-body">
                            <div id="map" style="height: 400px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Ambil lokasi otomatis
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('lat').value = position.coords.latitude;
                    document.getElementById('long').value = position.coords.longitude;
                },
                function(error) {
                    alert('Aktifkan lokasi untuk melakukan absen.');
                }
            );
        } else {
            alert('Browser Anda tidak mendukung fitur lokasi.');
        }

        // QR Scanner
        const html5QrCode = new Html5Qrcode("reader");

        function onScanSuccess(decodedText, decodedResult) {
            html5QrCode.stop().then(() => {
                document.getElementById('token_qr').value = decodedText;
                document.getElementById('submitBtn').disabled = false;
                document.getElementById('absenForm').submit(); // otomatis submit
            }).catch(err => {
                console.error('Stop scan error', err);
            });
        }

        Html5Qrcode.getCameras().then(cameras => {
            if (cameras && cameras.length) {
                const cameraId = cameras[0].id;
                html5QrCode.start(cameraId, {
                    fps: 10,
                    qrbox: 250
                }, onScanSuccess);
            } else {
                alert("Kamera tidak ditemukan.");
            }
        }).catch(err => {
            alert("Gagal mengakses kamera: " + err);
        });

        // Leaflet Map
        const kelasLat = {{ $kelas->latitude }};
        const kelasLng = {{ $kelas->longitude }};
        const radiusMeter = {{ $kelas->radius }};

        const map = L.map('map').setView([kelasLat, kelasLng], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        const kelasMarker = L.marker([kelasLat, kelasLng])
            .addTo(map)
            .bindPopup("Lokasi Kelas")
            .openPopup();

        const circle = L.circle([kelasLat, kelasLng], {
            color: 'green',
            fillColor: '#6bcf6b',
            fillOpacity: 0.2,
            radius: radiusMeter
        }).addTo(map);

        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;

                    const userMarker = L.marker([userLat, userLng], {
                        icon: L.icon({
                            iconUrl: 'https://cdn-icons-png.flaticon.com/512/149/149060.png',
                            iconSize: [25, 25],
                            iconAnchor: [12, 12]
                        })
                    }).addTo(map).bindPopup("Lokasi Anda");

                    const group = new L.featureGroup([kelasMarker, userMarker]);
                    map.fitBounds(group.getBounds().pad(0.5));
                },
                function(error) {
                    console.warn("Lokasi pengguna tidak tersedia.");
                }
            );
        }
    </script>
@endpush
