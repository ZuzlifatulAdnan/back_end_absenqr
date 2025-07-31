@extends('layouts.app')

@section('title', 'Data Guru')

@push('style')
    <!-- Tambahkan CSS tambahan jika diperlukan -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            @include('layouts.alert')
            <div class="section-header">
                <h1>Data Guru</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <form method="GET" action="{{ route('guru.index') }}" class="form-inline">
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

                        <a href="{{ route('guru.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Guru
                        </a>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Telepon</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($guru as $index => $item)
                                    <tr>
                                        <td>{{ $guru->firstItem() + $index }}</td>
                                        <td>{{ $item->user->name ?? '-' }}</td>
                                        <td>{{ $item->nip }}</td>
                                        <td>{{ $item->jenis_kelamin }}</td>
                                        <td>{{ $item->no_telepon }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('guru.edit', $item) }}"
                                                    class="btn btn-sm btn-icon btn-primary m-1" data-toggle="tooltip"
                                                    title="Edit Guru">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('guru.destroy', $item) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-icon m-1 btn-danger confirm-delete"
                                                        data-toggle="tooltip" title="Hapus Guru">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data guru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            Menampilkan {{ $guru->firstItem() }} - {{ $guru->lastItem() }} dari {{ $guru->total() }} data
                        </div>
                        <div>
                            {{ $guru->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
