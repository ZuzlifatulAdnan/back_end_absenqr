@extends('layouts.app')

@section('title', 'Rekap Absensi Siswa')

@push('style')
    <!-- Tambahkan CSS tambahan jika diperlukan -->
@endpush

@section('main')
<div class="main-content">
    @include('layouts.alert')
    <section class="section">
        <div class="section-header">
            <h1>Rekap Absensi | {{ $jadwal->mapel->nama }} | {{ $jadwal->kelas->nama }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('absen.index') }}">Absensi</a></div>
                <div class="breadcrumb-item">Rekap</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Tabel Rekap Kehadiran</h4>
                    <a href="{{ route('absen.rekap.export', $jadwal->id) }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama</th>
                                <th rowspan="2">Kelas</th>
                                @foreach ($tanggalAbsen as $pertemuanKe => $tanggal)
                                    <th>Pertemuan ke-{{ $pertemuanKe }}<br><small>{{ $tanggal }}</small></th>
                                @endforeach
                                <th rowspan="2">H</th>
                                <th rowspan="2">S</th>
                                <th rowspan="2">I</th>
                                <th rowspan="2">A</th>
                                <th rowspan="2">Jumlah Pertemuan</th>
                                <th rowspan="2">Total Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($absens as $siswa_id => $absenGroup)
                                @php
                                    $siswa = $absenGroup->first()->Siswa;
                                    $hadirCount = $absenGroup->where('status', 'H')->count();
                                    $sakitCount = $absenGroup->where('status', 'S')->count();
                                    $izinCount = $absenGroup->where('status', 'I')->count();
                                    $alfaCount = $absenGroup->where('status', 'A')->count();
                                    $totalKehadiran = $hadirCount + $sakitCount + $izinCount + $alfaCount;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $siswa->user->name }}</td>
                                    <td class="text-center">{{ $jadwal->kelas->nama }}</td>
                                    @foreach ($tanggalAbsen as $pertemuanKe => $tanggal)
                                        @php
                                            $status = $absenGroup->firstWhere('pertemuan_ke', $pertemuanKe)->status ?? '-';
                                        @endphp
                                        <td class="text-center">{{ $status }}</td>
                                    @endforeach
                                    <td class="text-center">{{ $hadirCount }}</td>
                                    <td class="text-center">{{ $sakitCount }}</td>
                                    <td class="text-center">{{ $izinCount }}</td>
                                    <td class="text-center">{{ $alfaCount }}</td>
                                    <td class="text-center">{{ $jumlahPertemuan }}</td>
                                    <td class="text-center">{{ $totalKehadiran }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <a href="{{ route('absen.index') }}" class="btn btn-warning">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
