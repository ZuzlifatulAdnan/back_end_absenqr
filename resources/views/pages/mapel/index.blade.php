@extends('layouts.app')

@section('title', 'Data Mapel')

@push('style')
    <!-- Tambahkan CSS tambahan jika diperlukan -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            @include('layouts.alert')
            <div class="section-header">
                <h1>Data Mata Pelajaran</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <form method="GET" action="{{ route('mapel.index') }}" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="nama" class="form-control" placeholder="Cari nama guru"
                                    value="{{ request('nama') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>

                        <a href="{{ route('mapel.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Mapel
                        </a>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mapel</th>
                                    <th>Kode</th>
                                    <th>Nama Guru</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mapel as $index => $item)
                                    <tr>
                                        <td>{{ $mapel->firstItem() + $index }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->guru->user->name ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('mapel.edit', $item) }}"
                                                    class="btn btn-sm btn-icon btn-primary m-1" data-toggle="tooltip"
                                                    title="Edit Mapel">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('mapel.destroy', $item) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-icon m-1 btn-danger confirm-delete"
                                                        data-toggle="tooltip" title="Hapus Mapel">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data mapel.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            Menampilkan {{ $mapel->firstItem() }} - {{ $mapel->lastItem() }} dari {{ $mapel->total() }}
                            data
                        </div>
                        <div>
                            {{ $mapel->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
