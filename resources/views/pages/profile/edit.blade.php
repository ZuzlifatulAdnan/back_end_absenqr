@extends('layouts.app')

@section('title', 'Edit Profil')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Profil</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('profile.index') }}">Profil</a></div>
                    <div class="breadcrumb-item active">Edit</div>
                </div>
            </div>

            <div class="section-body">
                @include('layouts.alert')
                <form action="{{ route('profile.update', Auth::user()) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Kolom Foto -->
                        <div class="col-lg-4">
                            <div class="card profile-widget">
                                <div class="profile-widget-header">
                                    <img id="image-preview"
                                        src="{{ Auth::user()->image ? asset('img/user/' . Auth::user()->image) : asset('img/avatar/avatar-1.png') }}"
                                        class="rounded-circle profile-widget-picture"
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                                <div class="p-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('foto') is-invalid @enderror"
                                            id="file-input" name="foto" accept="image/*" onchange="previewImage()">
                                        <label class="custom-file-label" for="file-input">Pilih foto</label>
                                        @error('foto')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="profile-widget-description">
                                    <div class="profile-widget-name">
                                        {{ Auth::user()->email }} - {{ Auth::user()->role }}
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary btn-block">Simpan</button>
                                    <a href="{{ route('profile.index') }}" class="btn btn-warning btn-block">Kembali</a>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Form -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Edit Akun</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Name -->
                                        <div class="form-group col-md-6">
                                            <label>Nama</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="name" value="{{ old('name', Auth::user()->name) }}">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div class="form-group col-md-6">
                                            <label>Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email', Auth::user()->email) }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    @if (Auth::user()->role === 'Siswa')
                                        <!-- Form Siswa -->
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>NIS</label>
                                                <input type="text" name="nis"
                                                    class="form-control @error('nis') is-invalid @enderror"
                                                    value="{{ old('nis', Auth::user()->siswa->nis ?? '') }}">
                                                @error('nis')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>NISN</label>
                                                <input type="text" name="nisn"
                                                    class="form-control @error('nisn') is-invalid @enderror"
                                                    value="{{ old('nisn', Auth::user()->siswa->nisn ?? '') }}">
                                                @error('nisn')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Jenis Kelamin</label>
                                                <select name="jenis_kelamin" class="form-control selectric">
                                                    <option value="Laki-laki"
                                                        {{ (Auth::user()->siswa->jenis_kelamin ?? '') === 'Laki-laki' ? 'selected' : '' }}>
                                                        Laki-laki</option>
                                                    <option value="Perempuan"
                                                        {{ (Auth::user()->siswa->jenis_kelamin ?? '') === 'Perempuan' ? 'selected' : '' }}>
                                                        Perempuan</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>No Telepon</label>
                                                <input type="text" name="no_telepon"
                                                    class="form-control @error('no_telepon') is-invalid @enderror"
                                                    value="{{ old('no_telepon', Auth::user()->siswa->no_telepon ?? '') }}">
                                                @error('no_telepon')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Kelas</label>
                                                <select name="kelas_id" class="form-control selectric">
                                                    @foreach ($kelasList as $kelas)
                                                        <option value="{{ $kelas->id }}"
                                                            {{ (Auth::user()->siswa->kelas_id ?? '') == $kelas->id ? 'selected' : '' }}>
                                                            {{ $kelas->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- Tambahan untuk orang tua --}}
                                            <div class="form-group col-md-6">
                                                <label>Nama Orang Tua</label>
                                                <input type="text" name="nama"
                                                    class="form-control @error('nama') is-invalid @enderror"
                                                    value="{{ old('nama', Auth::user()->siswa?->ortu?->nama ?? '') }}">
                                                @error('nama')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Alamat</label>
                                                <input type="text" name="alamat"
                                                    class="form-control @error('alamat') is-invalid @enderror"
                                                    value="{{ old('alamat', Auth::user()->siswa?->ortu?->alamat ?? '') }}">
                                                @error('alamat')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Pekerjaan</label>
                                                <input type="text" name="pekerjaan"
                                                    class="form-control @error('pekerjaan') is-invalid @enderror"
                                                    value="{{ old('pekerjaan', Auth::user()->siswa?->ortu?->pekerjaan ?? '') }}">
                                                @error('pekerjaan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>No Telepon Ortu</label>
                                                <input type="text" name="no_telepon_ortu"
                                                    class="form-control @error('no_telepon_ortu') is-invalid @enderror"
                                                    value="{{ old('no_telepon_ortu', Auth::user()->siswa?->ortu?->no_telepon ?? '') }}">
                                                @error('no_telepon_ortu')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Hubungan</label>
                                                <select name="hubungan" class="form-control selectric">
                                                    <option value="Ayah"
                                                        {{ (Auth::user()->siswa->hubungan ?? '') === 'Ayah' ? 'selected' : '' }}>
                                                        Ayah</option>
                                                    <option value="Ibu"
                                                        {{ (Auth::user()->siswa->jenis_kelamin ?? '') === 'Ibu' ? 'selected' : '' }}>
                                                        Ibu</option>
                                                    <option value="Wali"
                                                        {{ (Auth::user()->siswa->jenis_kelamin ?? '') === 'Wali' ? 'selected' : '' }}>
                                                        Wali</option>
                                                </select>
                                            </div>
                                        </div>
                                    @elseif (Auth::user()->role === 'Guru')
                                        <!-- Form Guru -->
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>NIP</label>
                                                <input type="text" name="nip"
                                                    class="form-control @error('nip') is-invalid @enderror"
                                                    value="{{ old('nip', Auth::user()->guru->nip ?? '') }}">
                                                @error('nip')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Jenis Kelamin</label>
                                                <select name="jenis_kelamin" class="form-control selectric">
                                                    <option value="Laki-laki"
                                                        {{ (Auth::user()->guru->jenis_kelamin ?? '') === 'Laki-laki' ? 'selected' : '' }}>
                                                        Laki-laki</option>
                                                    <option value="Perempuan"
                                                        {{ (Auth::user()->guru->jenis_kelamin ?? '') === 'Perempuan' ? 'selected' : '' }}>
                                                        Perempuan</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>No Telepon</label>
                                                <input type="text" name="no_telepon"
                                                    class="form-control @error('no_telepon') is-invalid @enderror"
                                                    value="{{ old('no_telepon', Auth::user()->guru->no_telepon ?? '') }}">
                                                @error('no_telepon')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script>
        function previewImage() {
            const fileInput = document.getElementById('file-input');
            const imagePreview = document.getElementById('image-preview');

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                }
                reader.readAsDataURL(fileInput.files[0]);
            }
        }
    </script>
@endpush
