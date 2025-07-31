@extends('layouts.app')

@section('title', 'Data Siswa')
@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/d') }}">
@endpush
@section('main')
    <div class="main-content">
        <section class="section">
            @include('layouts.alert')
            <div class="section-header">
                <h1>Data Siswa</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <form method="GET" action="{{ route('siswa.index') }}" class="form-inline">
                            <div class="input-group mr-2">
                                <select name="kelas" class="form-control" onchange="this.form.submit()">
                                    <option value="">Semua Kelas</option>
                                    @foreach ($listKelas as $kelas)
                                        <option value="{{ $kelas->id }}" {{ $kelas->id == $kelasId ? 'selected' : '' }}>
                                            {{ $kelas->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama siswa"
                                    value="{{ $keyword }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <a href="{{ route('siswa.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Siswa
                        </a>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Kelas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswas as $index => $siswa)
                                    <tr>
                                        <td>{{ $siswas->firstItem() + $index }}</td>
                                        <td>{{ $siswa->user->name }}</td>
                                        <td>{{ $siswa->user->email }}</td>
                                        <td>{{ $siswa->kelas->nama ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('siswa.edit', $siswa) }}"
                                                    class="btn btn-sm btn-icon btn-primary m-1" data-toggle="tooltip"
                                                    title="Edit Siswa">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <a href="{{ route('siswa.show', $siswa) }}"
                                                    class="btn btn-sm btn-icon btn-info m-1" data-toggle="tooltip"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <form action="{{ route('siswa.destroy', $siswa) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button  class="btn btn-sm btn-icon m-1 btn-danger confirm-delete"
                                                        data-toggle="tooltip" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data siswa.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            Menampilkan {{ $siswas->firstItem() }} - {{ $siswas->lastItem() }} dari
                            {{ $siswas->total() }} data
                        </div>
                        <div>
                            {{ $siswas->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
