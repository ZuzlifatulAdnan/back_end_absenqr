@extends('layouts.app')

@section('title', 'Edit Izin')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Izin</h1>
        </div>

        <form action="{{ route('izin.update', $izin->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-body">

                    <div class="form-group">
                        <label for="tanggal_izin">Tanggal Izin</label>
                        <input type="date" name="tanggal_izin" class="form-control" value="{{ old('tanggal_izin', $izin->tanggal_izin) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="alasan">Alasan</label>
                        <textarea name="alasan" class="form-control" rows="3" required>{{ old('alasan', $izin->alasan) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="bukti_surat">Upload Bukti Surat (Opsional)</label>
                        <input type="file" name="bukti_surat" class="form-control">
                        @if ($izin->bukti_surat)
                            <p class="mt-2">Saat ini: <a href="{{ asset('img/izin/' . $izin->bukti_surat) }}" target="_blank">Lihat</a></p>
                        @endif
                    </div>

                    <button class="btn btn-primary" type="submit">Update</button>
                    <a href="{{ route('izin.index') }}" class="btn btn-warning">Batal</a>
                </div>
            </div>
        </form>
    </section>
</div>
@endsection
