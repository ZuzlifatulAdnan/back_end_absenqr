@extends('layouts.app')

@section('title', 'Profile')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Profile</h1>
            </div>
            <div class="section-body">
                @include('layouts.alert')

                <div class="row">
                    <div class="col-md-4">
                        <div class="card profile-widget">
                            <div class="profile-widget-header text-center">
                                <img src="{{ Auth::user()->image ? asset('img/user/' . Auth::user()->image) : asset('img/avatar/avatar-1.png') }}"
                                    class="rounded-circle profile-widget-picture"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                            <div class="card-body">
                                <h5 class="text-center">{{ Auth::user()->name }}</h5>
                                <p class="text-muted text-center">{{ Auth::user()->email }} - {{ Auth::user()->role }}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('profile.edit', Auth::user()->id) }}"
                                    class="btn btn-primary btn-block">Edit Profil</a>
                                <a href="{{ route('profile.change-password-form', Auth::user()->id) }}"
                                    class="btn btn-warning btn-block">Ganti Password</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>Informasi Akun</h4>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th>Nama</th>
                                        <td>{{ Auth::user()->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ Auth::user()->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Role</th>
                                        <td>{{ Auth::user()->role }}</td>
                                    </tr>

                                    @if (Auth::user()->role === 'Siswa' && Auth::user()->siswa)
                                        <tr>
                                            <th>NIS</th>
                                            <td>{{ Auth::user()->siswa->nis }}</td>
                                        </tr>
                                        <tr>
                                            <th>NISN</th>
                                            <td>{{ Auth::user()->siswa->nisn }}</td>
                                        </tr>
                                        <tr>
                                            <th>Kelas</th>
                                            <td>{{ Auth::user()->siswa->kelas->nama ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Kelamin</th>
                                            <td>{{ Auth::user()->siswa->jenis_kelamin }}</td>
                                        </tr>
                                        <tr>
                                            <th>No HP</th>
                                            <td>{{ Auth::user()->siswa->no_telepon }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Ortu</th>
                                            <td>{{ Auth::user()->siswa->ortu->nama ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td>{{ Auth::user()->siswa->ortu->alamat ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Pekerjaan</th>
                                            <td>{{ Auth::user()->siswa->ortu->pekerjaan ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>No HP Ortu</th>
                                            <td>{{ Auth::user()->siswa->ortu->no_telepon ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Hubungan</th>
                                            <td>{{ Auth::user()->siswa->ortu->hubungan ?? '-' }}</td>
                                        </tr>
                                    @elseif (Auth::user()->role === 'Guru' && Auth::user()->guru)
                                        <tr>
                                            <th>NIP</th>
                                            <td>{{ Auth::user()->guru->nip }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Kelamin</th>
                                            <td>{{ Auth::user()->guru->jenis_kelamin }}</td>
                                        </tr>
                                        <tr>
                                            <th>No HP</th>
                                            <td>{{ Auth::user()->guru->no_telepon }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
