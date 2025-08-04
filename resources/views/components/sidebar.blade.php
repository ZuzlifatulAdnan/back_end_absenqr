<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('beranda.index') }}">
                <img src="{{ asset('img/logo/logo_nama.png') }}" alt="Logo" style="width: 200px; height: auto;">
            </a>
        </div>
        <br>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('beranda.index') }}">
                <img src="{{ asset('img/logo/logo.png') }}" alt="Logo" style="width: 50px; height: auto;">
            </a>
        </div>
        <br>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ $type_menu === 'beranda' ? 'active' : '' }}">
                <a href="{{ route('beranda.index') }}" class="nav-link">
                    <i class="fas fa-home"></i><span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">Master Data</li>
            @if (Auth::user()->role === 'Guru' || Auth::user()->role === 'Siswa')
                <li class="{{ $type_menu === 'absen' ? 'active' : '' }}">
                    <a href="{{ route('absen.index') }}" class="nav-link">
                        <i class="fas fa-clipboard-list"></i><span>Absen</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->role === 'Admin')
                <li class="nav-item dropdown {{ $type_menu === 'absen' ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-clipboard-list"></i><span>Kelola Absen</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ Request::is('absen*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('absen.index') }}">Absen</a>
                        </li>
                        <li class="{{ Request::is('absen/qr*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('qr.index') }}">Absen QR</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown {{ $type_menu === 'sekolah' ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-school"></i><span>Kelola Sekolah</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ Request::is('guru*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('guru.index') }}">Guru</a>
                        </li>
                        <li class="{{ Request::is('jadwal*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('jadwal.index') }}">Jadwal</a>
                        </li>
                        <li class="{{ Request::is('kelas*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('kelas.index') }}">Kelas</a>
                        </li>
                        <li class="{{ Request::is('mapel*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('mapel.index') }}">Mata Pelajaran</a>
                        </li>
                    </ul>
                </li>

                <li class="{{ $type_menu === 'siswa' ? 'active' : '' }}">
                    <a href="{{ route('siswa.index') }}" class="nav-link">
                        <i class="fas fa-user-graduate"></i><span>Kelola Siswa</span>
                    </a>
                </li>
                <li class="{{ $type_menu === 'user' ? 'active' : '' }}">
                    <a href="{{ route('user.index') }}" class="nav-link">
                        <i class="fas fa-users"></i><span>Users</span>
                    </a>
                </li>
            @endif
        </ul>
    </aside>
</div>
