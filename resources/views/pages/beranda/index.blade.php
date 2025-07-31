@extends('layouts.app')

@section('title', 'Beranda')

@push('style')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <style>
        .welcome-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: #f9f9f9;
            padding: 1rem;
            border-radius: 8px;
        }

        .welcome-card img {
            max-width: 120px;
        }

        .jadwal-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 1rem;
            padding: 1rem;
        }

        .jadwal-card h5 {
            margin: 0 0 0.5rem 0;
        }

        .jadwal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .datepicker-dropdown {
            z-index: 9999 !important;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        {{-- Welcome --}}
        <div class="welcome-card mb-4 bg-white">
            <img src="{{ asset('img/logo/hi.png' ) }}" alt="welcome">
            <div>
                <h5>Hai, {{ Auth::user()->role }}</h5>
                <p>Selamat datang di Sistem Presensi Kelas SMA KARTIKATAMA METRO.
                    Silahkan melakukan generate kode QR dengan mengatur ikon QR pada kelas yang diajar untuk membuka
                    presensi.</p>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="row">
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary"><i class="fas fa-users"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Users</h4>
                        </div>
                        <div class="card-body">{{ $jumlah_user }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Guru</h4>
                        </div>
                        <div class="card-body">{{ $jumlah_guru }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success"><i class="fas fa-user-graduate"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Siswa</h4>
                        </div>
                        <div class="card-body">{{ $jumlah_siswa }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning"><i class="fas fa-book"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Mapel</h4>
                        </div>
                        <div class="card-body">{{ $jumlah_mapel }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jadwal --}}
        <div class="card mt-4">
            <div class="card-body">
                <div class="jadwal-header">
                    <h4>Jadwal Mengajar</h4>
                </div>

                @if ($jadwal->isEmpty())
                    <div class="alert alert-info">Tidak ada jadwal untuk hari ini.</div>
                @else
                    @foreach ($jadwal as $item)
                        <div class="jadwal-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5>{{ strtoupper($item->mapel->nama_mapel) }}</h5>
                                    <p><i class="fas fa-clock"></i> {{ $item->jam_mulai }} - {{ $item->jam_selesai }} WIB
                                    </p>
                                    <p><i class="fas fa-users"></i> Kelas {{ $item->kelas->nama_kelas }}</p>
                                </div>
                                <div class="text-right">
                                    <p>Pertemuan ke 1</p>
                                    {{-- <a href="{{ route('generate.qr', $item->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-qrcode"></i> Generate Kode QR
                                    </a> --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush