@extends('layouts.app')

@section('title', 'Tambah Izin')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Izin</h1>
            </div>

            <div class="section-body">
                @include('layouts.alert')

                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('izin.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h4>Form Izin</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        {{-- Jika Admin, tampilkan dropdown siswa dan kelas --}}
                                        @if(Auth::user()->role === 'Admin')
                                            <div class="form-group col-md-6">
                                                <label for="siswa_id">Siswa</label>
                                                <select name="siswa_id" id="siswa_id" class="form-control selectric" required>
                                                    <option value="">Pilih Siswa</option>
                                                    @foreach($siswa as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->user->name ?? 'Tanpa Nama' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="kelas_id">Kelas</label>
                                                <select name="kelas_id" id="kelas_id" class="form-control selectric" required>
                                                    <option value="">Pilih Kelas</option>
                                                    @foreach($kelas as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @elseif(Auth::user()->role === 'Siswa' && Auth::user()->siswa)
                                            {{-- Jika siswa, isi otomatis --}}
                                            <input type="hidden" name="siswa_id" value="{{ Auth::user()->siswa->id }}">
                                            <input type="hidden" name="kelas_id" value="{{ Auth::user()->siswa->kelas_id }}">
                                        @else
                                            <div class="alert alert-danger col-12">
                                                Data siswa tidak ditemukan. Silakan hubungi admin.
                                            </div>
                                        @endif

                                        <div class="form-group col-md-6">
                                            <label for="tanggal_izin">Tanggal Izin</label>
                                            <input type="date" name="tanggal_izin" id="tanggal_izin" class="form-control" required>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="alasan">Alasan</label>
                                            <input type="text" name="alasan" id="alasan" class="form-control" placeholder="Contoh: Sakit" required>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="bukti_surat">Bukti Surat (jpg, jpeg, png, gif)</label>
                                            <input type="file" name="bukti_surat" id="bukti_surat" class="form-control" accept="image/*" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-right">
                                    <a href="{{ route('izin.index') }}" class="btn btn-warning">Kembali</a>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection
