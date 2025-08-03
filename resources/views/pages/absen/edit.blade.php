@extends('layouts.app')

@section('title', 'Edit Absensi Siswa')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Absensi | {{ $jadwal->mapel->nama . ' | ' . $jadwal->kelas->nama }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('/absen') }}">Absensi</a></div>
                    <div class="breadcrumb-item">Edit</div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Display Validation Errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <!-- Select All Hadir Button -->
                            <button type="button" class="btn btn-success mb-3" id="selectAllHadir">Select All
                                Hadir</button>
                            <form action="{{ route('absen.update', $absen->id) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <h5>Pertemuan Ke - {{ $absen->pertemuan_ke }}</h5>
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
                                                @php
                                                    // Ambil data absensi untuk siswa ini
                                                    $attendance = $absensi->get($n->id);
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <input type="hidden" value="{{ $n->id }}" name="siswa_id[]">
                                                        {{ $n->user->name }}
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="kehadiran[{{ $n->id }}]">
                                                            <option value="H"
                                                                {{ $attendance && $attendance->status == 'H' ? 'selected' : '' }}>
                                                                Hadir</option>
                                                            <option value="I"
                                                                {{ $attendance && $attendance->status == 'I' ? 'selected' : '' }}>
                                                                Izin</option>
                                                            <option value="S"
                                                                {{ $attendance && $attendance->status == 'S' ? 'selected' : '' }}>
                                                                Sakit</option>
                                                            <option value="A"
                                                                {{ $attendance && $attendance->status == 'A' ? 'selected' : '' }}>
                                                                Alpha</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer text-right">
                                    <button class="btn btn-primary mr-1" type="submit">Update</button>
                                    <a href="javascript:history.back()" class="btn btn-warning">Kembali</a>
                                </div>
                            </form>
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

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>
     <!-- Custom JS -->
     <script>
        document.getElementById('selectAllHadir').addEventListener('click', function() {
            document.querySelectorAll('select[name^="kehadiran"]').forEach(function(select) {
                select.value = 'H'; // Set to 'Hadir'
            });
        });
    </script>
@endpush
