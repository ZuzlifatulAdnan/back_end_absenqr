@extends('layouts.app')

@section('title', 'Tambah Kelas')

@push('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 300px; }
    </style>
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Tambah Kelas</h1>
        </div>

        <div class="section-body">
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        @include('layouts.alert')

                        <div class="form-group">
                            <label>Nama Kelas</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="">Pilih Status</option>
                                <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Radius (Meter)</label>
                            <input type="number" name="radius" class="form-control" value="{{ old('radius') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Lokasi (Pilih di Peta atau Isi Manual)</label>
                            <div id="map"></div>
                        </div>

                        <div class="form-group mt-2">
                            <label>Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="form-control" value="{{ old('latitude', '-6.200000') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="form-control" value="{{ old('longitude', '106.816666') }}" required>
                        </div>

                        <small class="text-muted">* Geser marker atau isi manual koordinat</small>
                    </div>

                    <div class="card-footer text-right">
                        <a href="{{ route('kelas.index') }}" class="btn btn-warning">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var defaultLat = parseFloat(document.getElementById('latitude').value) || -6.200000;
    var defaultLng = parseFloat(document.getElementById('longitude').value) || 106.816666;

    var map = L.map('map').setView([defaultLat, defaultLng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

    marker.on('dragend', function(e) {
        var latlng = marker.getLatLng();
        document.getElementById('latitude').value = latlng.lat.toFixed(6);
        document.getElementById('longitude').value = latlng.lng.toFixed(6);
    });

    function updateMarker() {
        var lat = parseFloat(document.getElementById('latitude').value);
        var lng = parseFloat(document.getElementById('longitude').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setLatLng([lat, lng]);
            map.setView([lat, lng], 13);
        }
    }

    document.getElementById('latitude').addEventListener('change', updateMarker);
    document.getElementById('longitude').addEventListener('change', updateMarker);
</script>
@endpush
