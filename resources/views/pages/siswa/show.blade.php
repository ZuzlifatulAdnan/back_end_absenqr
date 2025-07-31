@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Siswa</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Siswa</h4>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nama</dt>
                        <dd class="col-sm-8">{{ $siswa->user->name }}</dd>

                        <dt class="col-sm-4">NIS</dt>
                        <dd class="col-sm-8">{{ $siswa->nis }}</dd>

                        <dt class="col-sm-4">NISN</dt>
                        <dd class="col-sm-8">{{ $siswa->nisn }}</dd>

                        <dt class="col-sm-4">Kelas</dt>
                        <dd class="col-sm-8">{{ $siswa->kelas->nama }}</dd>

                        <dt class="col-sm-4">Jenis Kelamin</dt>
                        <dd class="col-sm-8">{{ $siswa->jenis_kelamin }}</dd>

                        <dt class="col-sm-4">No Telepon</dt>
                        <dd class="col-sm-8">{{ $siswa->no_telepon }}</dd>
                    </dl>
                </div>
            </div>

            @if ($ortu)
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Orang Tua</h4>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nama</dt>
                        <dd class="col-sm-8">{{ $ortu->nama }}</dd>

                        <dt class="col-sm-4">Alamat</dt>
                        <dd class="col-sm-8">{{ $ortu->alamat }}</dd>

                        <dt class="col-sm-4">Pekerjaan</dt>
                        <dd class="col-sm-8">{{ $ortu->pekerjaan }}</dd>

                        <dt class="col-sm-4">No Telepon</dt>
                        <dd class="col-sm-8">{{ $ortu->no_telepon }}</dd>

                        <dt class="col-sm-4">Hubungan</dt>
                        <dd class="col-sm-8">{{ $ortu->hubungan }}</dd>
                    </dl>
                </div>
            </div>
            @endif

            <a href="{{ route('siswa.index') }}" class="btn btn-warning mt-3">Kembali</a>
        </div>
    </section>
</div>
@endsection
