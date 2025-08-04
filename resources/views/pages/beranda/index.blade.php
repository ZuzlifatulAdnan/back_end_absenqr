@extends('layouts.app')

@section('title', 'Beranda')

@push('style')
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

        .jadwal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">

        {{-- Welcome --}}
        <div class="welcome-card mb-4 bg-white">
            <img src="{{ asset('img/logo/hi.png') }}" alt="welcome">
            <div>
                <h5>Hai, {{ Auth::user()->role }}</h5>
                <p>Selamat datang di Sistem Presensi Kelas SMA KARTIKATAMA METRO.</p>
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
                    <h4 class="mb-0">Jadwal Mengajar</h4>

                    <form method="GET" class="form-inline">
                        <label for="hari" class="mr-2">Hari:</label>
                        <select name="hari" id="hari" class="form-control" onchange="this.form.submit()">
                            <option value="">Semua</option>
                            @foreach ($daftar_hari as $hari)
                                <option value="{{ $hari }}" {{ $filterHari == $hari ? 'selected' : '' }}>
                                    {{ ucfirst($hari) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                @if ($jadwal->isEmpty())
                    <div class="alert alert-info">Tidak ada jadwal untuk hari ini{{ $filterHari ? " ($filterHari)" : '' }}.
                    </div>
                @else
                    @foreach ($jadwal as $item)
                        @php
                            $hariMap = [
                                'Senin' => 0,
                                'Selasa' => 1,
                                'Rabu' => 2,
                                'Kamis' => 3,
                                'Jumat' => 4,
                                'Sabtu' => 5,
                                'Minggu' => 6,
                            ];
                            $indexHari = $hariMap[$item->hari] ?? 0;
                            $tanggalPertemuan = \Carbon\Carbon::now()
                                ->startOfWeek()
                                ->addDays($indexHari)
                                ->format('d-m-Y');
                        @endphp

                        <div class="jadwal-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5>{{ strtoupper($item->mapel->nama ?? '-') }}</h5>
                                    <p><i class="fas fa-calendar-day"></i> Hari: {{ ucfirst($item->hari) }}</p>
                                    <p><i class="fas fa-clock"></i> {{ $item->jam_mulai }} - {{ $item->jam_selesai }} WIB
                                    </p>
                                    <p><i class="fas fa-users"></i> Kelas {{ $item->kelas->nama ?? '-' }}</p>
                                </div>
                                <div class="text-right">
                                    <p><i class="fas fa-calendar"></i> Tanggal: {{ $tanggalPertemuan }}</p>
                                    <div class="mt-3">
                                        @php $role = Auth::user()->role; @endphp

                                        @if ($role === 'Admin' || $role === 'Guru')
                                            <a href="{{ route('qr.view', $item->id) }}" class="btn btn-primary">
                                                <i class="fas fa-qrcode"></i> Kelola QR Presensi
                                            </a>
                                        @elseif ($role === 'Siswa')
                                            <a href="{{ route('absen.scan', $item) }}" class="btn btn-success">
                                                <i class="fas fa-camera"></i> Scan QR Presensi
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- PAGINATION --}}
                    <div class="mt-3">
                        {{ $jadwal->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
