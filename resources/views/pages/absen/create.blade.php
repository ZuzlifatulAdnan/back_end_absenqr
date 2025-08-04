@extends('layouts.app')

@section('title', 'Absensi Siswa')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Absensi | {{ $jadwals->Mapel->nama . ' | ' . $jadwals->Kelas->nama }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('/absen') }}">Absensi</a></div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('absen.store', $jadwals) }}" method="post">
                                @csrf
                                <h5>Pertemuan Ke - {{ $pertemuan }}</h5>

                                <!-- Button to select all students as "Hadir" -->
                                <button type="button" class="btn btn-success mb-3" id="selectAllHadir">Select All Hadir</button>

                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th style="width: 3%">No</th>
                                                <th>Nama Siswa</th>
                                                <th>Kehadiran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($siswas as $n)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <input type="hidden" value="{{ $pertemuan }}" name="pertemuan_ke">
                                                        <input type="hidden" value="{{ $n->id }}" name="siswa_id[]">
                                                        <input type="hidden" name="jadwal_id" value="{{ $n->id }}">

                                                        {{ $n->user->name }}
                                                    </td>
                                                    <td>
                                                        <select class="form-control kehadiran-select" name="kehadiran[{{ $n->id }}]">
                                                            <option value=""></option>
                                                            <option value="H">Hadir</option>
                                                            <option value="I">Izin</option>
                                                            <option value="S">Sakit</option>
                                                            <option value="A">Alpha</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary mr-1" type="submit">Tambah</button>
                            <a href="{{ url('/absen') }}" class="btn btn-warning">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>

    <script>
        // JavaScript to handle "Select All Hadir" functionality
        document.getElementById('selectAllHadir').addEventListener('click', function() {
            const selectElements = document.querySelectorAll('.kehadiran-select');
            selectElements.forEach(function(selectElement) {
                selectElement.value = 'H'; // Set the value to "Hadir"
            });
        });
    </script>
@endpush
