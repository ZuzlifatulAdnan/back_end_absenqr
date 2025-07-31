@extends('layouts.app')

@section('title', 'Users')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <style>
        #image-preview {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #ddd;
            padding: 10px;
            width: 200px;
            height: 200px;
            margin-top: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            position: relative;
            overflow: hidden;
        }

        #image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
    </style>
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin')
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            @include('layouts.alert')
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('user.store') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Tambah User</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-6 mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    value="{{ old('name') }}" placeholder="Masukkan Nama" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6 mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                                    name="email" value="{{ old('email') }}" placeholder="Masukkan Email"
                                                    required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" name="password"
                                                    placeholder="Masukkan Password 8 Karakter" required>
                                                @error('password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6 mb-3">
                                                <label for="role" class="form-label">Role</label>
                                                <select id="role" class="form-control" name="role" required
                                                    onchange="toggleAdditionalInputs()">
                                                    <option value="">Pilih Status</option>
                                                    <option value="Admin">Admin</option>
                                                    <option value="Guru">Guru</option>
                                                    <option value="Siswa">Siswa</option>
                                                </select>
                                                @error('role')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="image" class="form-label">Image</label>
                                                <input type="file"
                                                    class="form-control @error('image') is-invalid @enderror" id="image"
                                                    name="image" accept="image/*" >
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div id="image-preview">
                                                    <span>Preview Image</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary mt-2">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @else
    @endif

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('image-preview');

            // Preview image when file is selected
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        alert('Please upload a valid image file.');
                        this.value = '';
                        imagePreview.innerHTML = `<span>Preview Image</span>`;
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        imagePreview.innerHTML =
                            `<img src="${event.target.result}" alt="Image Preview">`;
                    }
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.innerHTML = `<span>Preview Image</span>`;
                }
            });
        });
    </script>
@endpush
