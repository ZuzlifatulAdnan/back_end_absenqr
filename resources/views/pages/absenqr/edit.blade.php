@extends('layouts.app')

@section('title', 'Edit Absen QR')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Absen QR</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('qr.update', $absenqr->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="jadwal_id">Mata Pelajaran</label>
                            <select name="jadwal_id" class="form-control" required>
                                @foreach ($jadwal as $j)
                                    <option value="{{ $j->id }}" {{ $absenqr->jadwal_id == $j->id ? 'selected' : '' }}>
                                        {{ $j->mapel->nama }} - {{ $j->kelas->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_absen">Tanggal Absen</label>
                            <input type="date" name="tanggal_absen" class="form-control" value="{{ $absenqr->tanggal_absen }}" required>
                        </div>

                        <div class="form-group">
                            <label for="expired_at">Expired QR</label>
                            <input type="datetime-local" name="expired_at" class="form-control" value="{{ \Carbon\Carbon::parse($absenqr->expired_at)->format('Y-m-d\TH:i') }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="javascript:history.back()" class="btn btn-warning">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
