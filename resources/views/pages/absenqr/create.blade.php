@extends('layouts.app')

@section('title', 'Tambah Absen QR')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Absen QR</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('qr.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="jadwal_id">Mata Pelajaran</label>
                                <select name="jadwal_id" class="form-control select2" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach ($jadwal as $j)
                                        <option value="{{ $j->id }}">{{ $j->mapel->nama }} - {{ $j->kelas->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_absen">Tanggal Absen</label>
                                <input type="date" name="tanggal_absen" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="expired_at">Expired QR</label>
                                <input type="datetime-local" name="expired_at" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('absenqr.index') }}" class="btn btn-warning">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
