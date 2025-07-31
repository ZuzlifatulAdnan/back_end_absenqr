@extends('layouts.app')

@section('title', 'Edit User')

@push('style')
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
                            <form action="{{ route('user.update', $user->id) }}" enctype="multipart/form-data"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Edit User</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-6 mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                                    name="name" value="{{ old('name', $user->name) }}"
                                                    placeholder="Masukkan Nama" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6 mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                                    name="email" value="{{ old('email', $user->email) }}"
                                                    placeholder="Masukkan Email" required>
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
                                                    placeholder="Masukkan Password Baru (Opsional)">
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6 mb-3">
                                                <label for="role" class="form-label">Role</label>
                                                <select id="role" class="form-control" name="role" required
                                                    onchange="toggleAdditionalInputs()">
                                                    <option value="">Pilih Status</option>
                                                    <option value="Admin"
                                                        {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>Admin
                                                    </option>
                                                    <option value="Guru"
                                                        {{ old('role', $user->role) == 'Guru' ? 'selected' : '' }}>Guru
                                                    </option>
                                                    <option value="Siswa"
                                                        {{ old('role', $user->role) == 'Siswa' ? 'selected' : '' }}>
                                                        Siswa</option>
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
                                                    name="image" accept="image/*">
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div id="image-preview">
                                                    @if ($user->image)
                                                        <img src="{{ asset('img/user/' . $user->image) }}"
                                                            alt="Image Preview">
                                                    @else
                                                        <span>Preview Image</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary mt-2">Update</button>
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

        function toggleAdditionalInputs() {
            const status = document.getElementById('role').value;
            const npmGroup = document.getElementById('npm-group');
            const nipGroup = document.getElementById('nip-group');

            if (status === 'Mahasiswa') {
                npmGroup.classList.remove('d-none');
                nipGroup.classList.add('d-none');
            } else if (status === 'Dosen') {
                nipGroup.classList.remove('d-none');
                npmGroup.classList.add('d-none');
            } else {
                npmGroup.classList.add('d-none');
                nipGroup.classList.add('d-none');
            }
        }
    </script>
@endpush
