@extends('layouts.app')

@section('title', 'Absen')

@push('style')
    <!-- Tambahkan CSS tambahan jika diperlukan -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            @include('layouts.alert')
            <div class="section-header">
                <h1>Absen</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <form method="GET" action="{{ route('absen.index') }}" class="form-inline">
                            <div class="input-group mr-2">
                                <input type="text" name="q" class="form-control" placeholder="Cari guru atau mapel"
                                    value="{{ $searchQuery }}">
                            </div>

                            @if (Auth::user()->role != 'siswa')
                                <div class="input-group mr-2">
                                    <select name="kelas_id" class="form-control">
                                        <option value="">-- Semua Kelas --</option>
                                        @foreach ($kelasList as $kelas)
                                            <option value="{{ $kelas->id }}"
                                                {{ $selectedKelas == $kelas->id ? 'selected' : '' }}>
                                                {{ $kelas->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="input-group mr-2">
                                <select name="hari" class="form-control">
                                    <option value="">-- Semua Hari --</option>
                                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                                        <option value="{{ $day }}" {{ $selectedHari == $day ? 'selected' : '' }}>
                                            {{ $day }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Filter</button>
                            </div>
                        </form>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    @if (Auth::user()->role == 'guru')
                                    @else
                                        <th>Nama Guru</th>
                                    @endif
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Hari</th>
                                    <th>Jam Mengajar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jadwals as $index => $item)
                                    <tr>
                                        <td>{{ $jadwals->firstItem() + $index }}</td>
                                        @if (Auth::user()->role == 'guru')
                                        @else
                                            <td>{{ $item->guru->user->name }}</td>
                                        @endif

                                        <td>{{ $item->mapel->nama }}</td>
                                        <td>{{ $item->kelas->nama }}</td>
                                        <td>{{ $item->hari }}</td>
                                        <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                                        <td class="text-center">
                                            @if (Auth::user()->role == 'Admin')
                                                <a href="{{ route('absen.create', $item) }}"
                                                    class="btn btn-sm btn-icon btn-success m-1"><i class="fas fa-plus"></i>
                                                </a>
                                                <a href="{{ route('absen.show', $item->id) }}"
                                                    class="btn btn-sm btn-icon btn-info m-1">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                                </a>
                                            @elseif(Auth::user()->role == 'Guru')
                                                <a href="{{ route('absen.create', $item) }}"
                                                    class="btn btn-sm btn-icon btn-success m-1"><i class="fas fa-plus"></i>
                                                </a>
                                                <a href="{{ route('absen.show', $item->id) }}"
                                                    class="btn btn-sm btn-icon btn-info m-1">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                            @endif

                                            <a href="{{ route('absen.rekap', $item) }}"
                                                class="btn btn-sm btn-icon btn-primary m-1"><i class="fas fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data Jadwal Absensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            Menampilkan {{ $jadwals->firstItem() }} - {{ $jadwals->lastItem() }} dari
                            {{ $jadwals->total() }} data
                        </div>
                        <div>
                            {{ $jadwals->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
