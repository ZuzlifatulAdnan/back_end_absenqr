@extends('layouts.app')

@section('title', 'Profile')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
@endpush
@php
    use Carbon\Carbon;
@endphp
@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>QR</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Absen QR</a></div>
                    <div class="breadcrumb-item">QR</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>QR Absen untuk tanggal
                                    {{ Carbon::parse($absenqr->tanggal_absen)->locale('id')->isoFormat('DD MMMM YYYY') }}
                                </h4>
                                <a href="{{ route('absenqr.download', $absenqr->id) }}" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Download PDF
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Qr Kode </h5>
                                        <div class="qr-code-container text-center mb-3">
                                            {!! QrCode::size(200)->generate($absenqr->token_qr) !!}
                                        </div>
                                        <p class="text-center"><strong>Kode:</strong> {{ $absenqr->token_qr }}</p>
                                        <a href="{{ route('absenqr.index') }}" class="btn btn-warning">Kembali</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>

    <!-- Page Specific JS File -->
@endpush
