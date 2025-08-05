@extends('layouts.app')

@section('title', 'Data Izin')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Izin</h1>
            </div>

            <div class="section-body">
                @include('layouts.alert')

                <div class="card">
                    <div class="card-body">
                        {{-- Form Filter --}}
                        <form action="{{ route('izin.index') }}" method="GET" class="form-row mb-4">
                            @if (Auth::user()->role !== 'Siswa')
                                <div class="col-md-3">
                                    <select name="kelas_id" class="form-control">
                                        <option value="">-- Semua Kelas --</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Cari nama siswa...">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-block" type="submit">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </form>

                        {{-- Tombol Tambah --}}
                        @if (in_array(Auth::user()->role, ['Admin', 'Siswa']))
                            <div class="mb-3 text-right">
                                <a href="{{ route('izin.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Izin
                                </a>
                            </div>
                        @endif

                        {{-- Tabel Data Izin --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($izin as $index => $row)
                                        <tr>
                                            <td>{{ $izin->firstItem() + $index }}</td>
                                            <td>{{ $row->siswa->user->name ?? '-' }}</td>
                                            <td>{{ $row->siswa->kelas->nama ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }}</td>
                                            <td class="text-center">
                                                {{-- Show button --}}
                                                <a href="{{ route('izin.show', $row->id) }}" class="btn btn-sm btn-info" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @php
                                                    $isAdmin = in_array(Auth::user()->role, ['Admin']);
                                                @endphp

                                                {{-- Edit & Delete hanya jika Admin, Guru, atau pemilik --}}
                                                @if ($isAdmin)
                                                    <a href="{{ route('izin.edit', $row->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('izin.destroy', $row->id) }}" method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-3">
                            {{ $izin->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
