@extends('layouts.app')

@section('title', 'Edit Mapel')

@push('style')
    <!-- Tambahkan CSS Select2 jika diperlukan -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Mata Pelajaran</h1>
            </div>

            <div class="section-body">
                @include('layouts.alert')

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('mapel.update', $mapel->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="guru_id">Guru Pengampu</label>
                                <select name="guru_id" id="guru_id"
                                    class="form-control @error('guru_id') is-invalid @enderror">
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach ($gurus as $guru)
                                        <option value="{{ $guru->id }}"
                                            {{ $mapel->guru_id == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->user->name ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('guru_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="nama">Nama Mapel</label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama', $mapel->nama) }}"
                                    class="form-control @error('nama') is-invalid @enderror">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="kode">Kode Mapel</label>
                                <input type="text" name="kode" id="kode" value="{{ old('kode', $mapel->kode) }}"
                                    class="form-control @error('kode') is-invalid @enderror">
                                @error('kode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                            <a href="{{ route('mapel.index') }}" class="btn btn-warning">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
