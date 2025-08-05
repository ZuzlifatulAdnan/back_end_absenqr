@extends('layouts.app')

@section('title', 'Detail Izin')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Izin</h1>
        </div>

        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nama Siswa</dt>
                    <dd class="col-sm-9">{{ $izin->siswa->user->name }}</dd>

                    <dt class="col-sm-3">Kelas</dt>
                    <dd class="col-sm-9">{{ $izin->kelas->nama }}</dd>

                    <dt class="col-sm-3">Tanggal Izin</dt>
                    <dd class="col-sm-9">{{ $izin->tanggal_izin }}</dd>

                    <dt class="col-sm-3">Alasan</dt>
                    <dd class="col-sm-9">{{ $izin->alasan }}</dd>

                    <dt class="col-sm-3">Bukti Surat</dt>
                    <dd class="col-sm-9">
                        @if ($izin->bukti_surat)
                            <a href="{{ asset('img/izin/' . $izin->bukti_surat) }}" target="_blank" class="btn btn-info btn-sm">Lihat</a>
                            <a href="{{ asset('img/izin/' . $izin->bukti_surat) }}" download class="btn btn-success btn-sm">Download</a>
                        @else
                            Tidak ada bukti
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

        <a href="{{ route('izin.index') }}" class="btn btn-warning">Kembali</a>
    </section>
</div>
@endsection
