@extends('layouts.app')

@section('title', 'Data Absen QR')

@push('style')
    <!-- Tambahkan CSS tambahan jika diperlukan -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            @include('layouts.alert')
            <div class="section-header">
                <h1>Data Absen QR</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <form method="GET" action="{{ route('qr.index') }}" class="form-inline">
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

                        <a href="{{ route('qr.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Absen QR
                        </a>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Tanggal</th>
                                    <th>Expired At</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($absenqr as $index => $item)
                                    <tr>
                                        <td>{{ $absenqr->firstItem() + $index }}</td>
                                        <td>{{ $item->jadwal->mapel->nama ?? '-' }}</td>
                                        <td>{{ $item->jadwal->kelas->nama }}</td>
                                        <td>{{ $item->tanggal_absen }}</td>
                                        <td>{{ $item->expired_at }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('qr.edit', $item->id) }}"
                                                    class="btn btn-sm btn-icon btn-primary m-1" data-toggle="tooltip"
                                                    title="Edit Guru">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href='{{ route('qr.show', $item->id) }}'
                                                    class="btn btn-sm btn-icon btn-info m-1" data-toggle="tooltip"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('qr.destroy', $item) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-icon m-1 btn-danger confirm-delete"
                                                        data-toggle="tooltip" title="Hapus Qr">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data absenqr.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            Menampilkan {{ $absenqr->firstItem() }} - {{ $absenqr->lastItem() }} dari
                            {{ $absenqr->total() }} data
                        </div>
                        <div>
                            {{ $absenqr->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
