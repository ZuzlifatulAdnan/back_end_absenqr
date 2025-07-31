@extends('layouts.app')

@section('title', 'Data Kelas')

@push('style')
    <!-- Tambahkan jika ada CSS tambahan -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            @include('layouts.alert')
            <div class="section-header">
                <h1>Data Kelas</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <form method="GET" action="{{ route('kelas.index') }}" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="nama" class="form-control" placeholder="Cari nama kelas"
                                    value="{{ request('nama') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>

                        <a href="{{ route('kelas.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Kelas
                        </a>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Status</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Radius</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kelas as $index => $item)
                                    <tr>
                                        <td>{{ $kelas->firstItem() + $index }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ $item->latitude }}</td>
                                        <td>{{ $item->longitude }}</td>
                                        <td>{{ $item->radius }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('kelas.edit', $item) }}"
                                                    class="btn btn-sm btn-icon btn-primary m-1" data-toggle="tooltip"
                                                    title="Edit Kelas">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('kelas.destroy', $item) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-icon m-1 btn-danger confirm-delete"
                                                        data-toggle="tooltip" title="Hapus Kelas">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data kelas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            Menampilkan {{ $kelas->firstItem() }} - {{ $kelas->lastItem() }} dari {{ $kelas->total() }}
                            data
                        </div>
                        <div>
                            {{ $kelas->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
