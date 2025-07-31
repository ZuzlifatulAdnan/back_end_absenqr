<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('beranda.index') }}">
                <img src="{{ asset('img/logo/logo_nama.png') }}" alt="Logo" style="width: 180px; height: auto;">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('beranda.index') }}">
                <img src="{{ asset('img/logo/logo.png') }}" alt="Logo" style="width: 40px; height: auto;">
            </a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ $type_menu === 'beranda' ? 'active' : '' }}">
                <a href="{{ route('beranda.index') }}" class="nav-link">
                    <i class="fas fa-home"></i><span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">Master Data</li>
            <li class="nav-item dropdown {{ $type_menu === 'absen' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-clipboard-list"></i><span>Kelola Absen</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('absen*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('absen.index') }}">Absen</a>
                    </li>
                    <li class="{{ Request::is('absenqr*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('absenqr.index') }}">Absen QR</a>
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

            @if (Auth::user()->role === 'Admin')
                <li class="{{ $type_menu === 'user' ? 'active' : '' }}">
                    <a href="{{ route('user.index') }}" class="nav-link">
                        <i class="fas fa-users"></i><span>Users</span>
                    </a>
                </li>
            @endif
        </ul>
    </aside>
</div>
