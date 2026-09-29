<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <!-- Sidenav Menu Heading (Core)-->
            <div class="sidenav-menu-heading">Menu</div>

            <!-- Sidenav Link (Dashboard)-->
            <a class="nav-link {{ (request()->is('user/dashboard-user')) ? 'active' : '' }}" href="{{ route('user-dashboard') }}">
                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                Dashboard
            </a>

            <!-- Absensi -->
            <a class="nav-link {{ (request()->is('user/absensi') || request()->is('user/absensi-pulang')) ? 'active' : '' }}" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseTransaksi" aria-expanded="false" aria-controls="collapseSettings">
                <div class="nav-link-icon"><i data-feather="clock"></i></div>
                Absensi 7 Karakter
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse {{ (request()->is('user/absensi') || request()->is('user/absensi-pulang')) ? 'show' : '' }}" id="collapseTransaksi" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav">
                    <a class="nav-link {{ (request()->is('user/absensi')) ? 'active' : '' }}" href="{{ route('absensi.index') }}">Absen Masuk</a>
                    <a class="nav-link {{ (request()->is('user/absensi-pulang')) ? 'active' : '' }}" href="{{ route('absensi-pulang') }}">Absen Pulang</a>
                </nav>
            </div>

            <!-- Laporan -->
            <a class="nav-link {{ (request()->is('user/report-user*')) ? 'active' : '' }}" href="{{ route('report-user.index') }}">
                <div class="nav-link-icon"><i data-feather="file-text"></i></div>
                Laporan Absensi
            </a>

            <!-- Profile -->
            <a class="nav-link {{ (request()->is('user/setting-user*')) ? 'active' : '' }}" href="{{ route('setting-user.index') }}">
                <div class="nav-link-icon"><i data-feather="settings"></i></div>
                Profile Mahasiswa
            </a>

            <!-- Jadwal 7 Karakter -->
            <a class="nav-link  {{ (request()->is('user/jadwal-user*')) ? 'active' : '' }}" href="{{ route('jadwal-user.index') }}">
                <div class="nav-link-icon"><i data-feather="calendar"></i></div>
                Jadwal 7 Karakter
            </a>

        </div>
    </div>

    <!-- Sidenav Footer-->
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Login Sebagai:</div>
            <div class="sidenav-footer-title">{{ Auth::user()->name }}</div>
        </div>
    </div>
</nav>
