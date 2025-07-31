@extends('layouts.app')

@section('title', 'Data Jadwal')

@push('style')
    <link href="{{ asset('library/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            @include('layouts.alert')

            <div class="section-header">
                <h1>Data Jadwal Pelajaran</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <form method="GET" action="{{ route('jadwal.index') }}" class="form-inline mb-2 mb-md-0">
                            <div class="form-row">
                                <div class="col mr-1">
                                    <select name="kelas_id" class="form-control" onchange="this.form.submit()">
                                        <option value="">-- Semua Kelas --</option>
                                        @foreach ($kelasList as $k)
                                            <option value="{{ $k->id }}"
                                                {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col mr-1">
                                    <select name="hari" class="form-control" onchange="this.form.submit()">
                                        <option value="">-- Semua Hari --</option>
                                        @foreach ($hariList as $hari)
                                            <option value="{{ $hari }}"
                                                {{ request('hari') == $hari ? 'selected' : '' }}>
                                                {{ $hari }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col mr-1">
                                    <input type="text" name="nama" class="form-control" placeholder="Cari guru/mapel"
                                        value="{{ request('nama') }}">
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>

                        <a href="{{ route('jadwal.create') }}" class="btn btn-primary ml-md-2">
                            <i class="fas fa-plus"></i> Tambah Jadwal
                        </a>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Guru</th>
                                    <th>Kelas</th>
                                    <th>Mapel</th>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jadwal as $index => $item)
                                    <tr>
                                        <td>{{ $jadwal->firstItem() + $index }}</td>
                                        <td>{{ $item->guru->user->name ?? '-' }}</td>
                                        <td>{{ $item->kelas->nama ?? '-' }}</td>
                                        <td>{{ $item->mapel->nama ?? '-' }}</td>
                                        <td>{{ $item->hari }}</td>
                                        <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('jadwal.edit', $item) }}"
                                                    class="btn btn-sm btn-icon btn-primary m-1" title="Edit Jadwal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('jadwal.destroy', $item) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-icon btn-danger m-1" title="Hapus Jadwal">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data jadwal.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            Menampilkan {{ $jadwal->firstItem() }} - {{ $jadwal->lastItem() }} dari
                            {{ $jadwal->total() }} data
                        </div>
                        <div>
                            {{ $jadwal->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
