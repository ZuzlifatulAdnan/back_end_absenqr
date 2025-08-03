@extends('layouts.app')

@section('title', 'Detail Absensi')

@push('style')
    <!-- Tambahkan CSS tambahan jika diperlukan -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            @include('layouts.alert')

            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Detail Absensi - {{ $jadwal->mapel->nama }} | {{ $jadwal->kelas->nama }}</h1>
                <a href="{{ route('absen.index') }}" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <form method="GET" action="{{ url()->current() }}" class="w-100">
                            <div class="row">
                                @php
                                    $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                @endphp

                                <div class="form-group col-md-3">
                                    <select name="hari" class="form-control">
                                        <option value="">-- Semua Hari --</option>
                                        @foreach ($hariList as $day)
                                            <option value="{{ $day }}" {{ request('hari') == $day ? 'selected' : '' }}>
                                                {{ $day }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <select name="kelas_id" class="form-control">
                                        <option value="">-- Semua Kelas --</option>
                                        @foreach ($kelasList as $kelas)
                                            <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                                {{ $kelas->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
                                </div>

                                <div class="form-group col-md-3 d-flex align-items-center">
                                    <button class="btn btn-primary mr-2" type="submit">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="{{ url()->current() }}" class="btn btn-warning ml-2">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Pertemuan</th>
                                    <th class="text-center">Kelas</th>
                                    <th class="text-center">Hari</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Hadir / Siswa</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($absen as $index => $a)
                                    <tr>
                                        <td class="text-center">{{ $absen->firstItem() + $index }}</td>
                                        <td class="text-center">{{ $a->pertemuan_ke }}</td>
                                        <td class="text-center">{{ $a->jadwal->kelas->nama }}</td>
                                        <td class="text-center">{{ $a->jadwal->hari }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($a->tanggal_absen)->format('d-m-Y') }}</td>
                                        <td class="text-center">{{ $a->total_hadir }} / {{ $a->total_siswa }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('absen.edit', $a->id) }}" class="btn btn-sm btn-warning" title="Edit Absen">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data absensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            Menampilkan {{ $absen->firstItem() }} - {{ $absen->lastItem() }} dari {{ $absen->total() }} data
                        </div>
                        <div>
                            {{ $absen->withQueryString()->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
