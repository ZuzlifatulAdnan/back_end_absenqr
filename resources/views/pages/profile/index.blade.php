@extends('layouts.app')

@section('title', 'Profile')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        @include('layouts.alert')
        <section class="section">
            <div class="section-header">
                <h1>Profile</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('profile.index') }}">Profile</a></div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <form enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-4">
                                    <div class="card profile-widget">
                                        <div class="profile-widget-header">
                                            <img alt="image"
                                                src="{{ Auth::user()->image ? asset('img/user/' . Auth::user()->image) : asset('img/avatar/avatar-1.png') }}"
                                                class="rounded-circle profile-widget-picture" width="100" height="100">

                                        </div>
                                        <div class="profile-widget-description">
                                            <div class="profile-widget-name">
                                                {{ Auth::user()->email }} -
                                                {{ Auth::user()->role }}
                                            </div>

                                        </div>
                                        <div class="card-footer mb-2">
                                            <a href="{{ route('profile.edit', Auth::user()) }}"
                                                class="btn btn-primary btn-lg btn-block">
                                                Edit Akun
                                            </a>
                                            <a href="{{ route('profile.change-password-form', Auth::user()) }}"
                                                class="btn btn-warning btn-lg btn-block">Ganti Password</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Profile</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-6 mb-3">
                                                    <label for="name" class="form-label">Nama</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        readonly value="{{ Auth::user()->name }}">
                                                </div>
                                                <div class="form-group col-md-6 mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="email" id="email"
                                                        readonly value="{{ Auth::user()->email }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script>
        function previewImage() {
            var fileInput = document.getElementById('file-input');
            var imagePreview = document.getElementById('image-preview');

            // Cek apakah ada file yang dipilih
            if (fileInput.files && fileInput.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    // Menetapkan src gambar pratinjau dengan data URL dari file yang dipilih
                    imagePreview.src = e.target.result;
                }

                // Membaca file sebagai data URL
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                // Jika tidak ada file yang dipilih, menetapkan src ke gambar default
                imagePreview.src = "{{ asset('img/avatar/avatar-1.png') }}";
            }
        }
    </script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/components-table.js') }}"></script>
@endpush
