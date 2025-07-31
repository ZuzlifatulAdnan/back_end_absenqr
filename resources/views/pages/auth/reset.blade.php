@extends('layouts.auth')

@section('title', 'Reset Passwprd')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Reset Password</h4>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.update') }}" class="needs-validation" novalidate="">
                @csrf
                <div class="form-group">
                    <input id="token" type="hidden"
                        class="form-control @error('token') is-invalid

                    @enderror"
                        value="{{ old('token') ?? $request->token }}" name="token" tabindex="1" autofocus readonly>
                    @error('token')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email"
                        class="form-control @error('email') is-invalid

                    @enderror"
                        value="{{ old('email') ?? $request->email }}" name="email" tabindex="1" readonly>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="d-block">
                        <label for="password" class="control-label">Password Baru</label>
                    </div>
                    <input id="password" type="password"
                        class="form-control @error('password') is-invalid

                    @enderror" name="password"
                        tabindex="2" placeholder="Masukkan Password Baru"  required>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="d-block">
                        <label for="password" class="control-label">Konfirmasi Password</label>
                    </div>
                    <input id="password" type="password" placeholder="Masukkan Konfirmasi password" 
                        class="form-control @error('password') is-invalid

                    @enderror"
                        name="password_confirmation" tabindex="2" required>
                    @error('password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                        Ubah Password
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush