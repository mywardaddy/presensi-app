<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <!-- Sidenav Menu Heading (Core)-->
            <div class="sidenav-menu-heading">Menu</div>

            <!-- Sidenav Link (Dashboard)-->
            <a class="nav-link {{ (request()->is('admin/dashboard')) ? 'active' : '' }}" href="{{ route('admin-dashboard') }}">
                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                Dashboard
            </a>

            <!-- Data Absensi 7 Karakter-->
            <a class="nav-link  {{ (request()->is('admin/presensi*')) ? 'active' : '' }}" href="{{ route('presensi.index') }}">
                <div class="nav-link-icon"><i data-feather="clock"></i></div>
                Data Absensi 7 Karakter
            </a>

            <!-- Data Instansi-->
            <a class="nav-link  {{ (request()->is('admin/instansi*')) ? 'active' : '' }}" href="{{ route('instansi.index') }}">
                <div class="nav-link-icon"><i data-feather="briefcase"></i></div>
                Data Instansi
            </a>

            <!-- Data Admin & User-->
            <a class="nav-link  {{ (request()->is('admin/user*')) ? 'active' : '' }}" href="{{ route('user.index') }}">
                <div class="nav-link-icon"><i data-feather="users"></i></div>
                Data Admin & User
            </a>

            <!-- Laporan Absensi-->
            <a class="nav-link {{ (request()->is('admin/report*') || request()->is('admin/laporan-total')) ? 'active' : '' }}" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseReport" aria-expanded="false" aria-controls="collapseSettings">
                <div class="nav-link-icon"><i data-feather="file-text"></i></div>
                Laporan Absensi
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>

            <!-- Laporan Absensi-->
            <div class="collapse {{ (request()->is('admin/report') || request()->is('admin/laporan-total')) ? 'show' : '' }}" id="collapseReport" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav">
                    <a class="nav-link {{ (request()->is('admin/report')) ? 'active' : '' }}" href="{{ route('report.index') }}">Laporan Absensi Mahasiswa</a>
                    <a class="nav-link {{ (request()->is('admin/laporan-total')) ? 'active' : '' }}" href="{{ route('laporan-total') }}">Laporan Absensi Total</a>
                </nav>
            </div>

            <!-- Profile-->
            <a class="nav-link {{ (request()->is('admin/setting*')) ? 'active' : '' }}" href="{{ route('setting.index') }}">
                <div class="nav-link-icon"><i data-feather="settings"></i></div>
                Profile Admin
            </a>

            <!-- Jadwal 7 Karakter-->
            <a class="nav-link  {{ (request()->is('admin/jadwal*')) ? 'active' : '' }}" href="{{ route('jadwal.index') }}">
                <div class="nav-link-icon"><i data-feather="calendar"></i></div>
                Jadwal 7 Karakter
            </a>
        </div>
    </div>
    
    <!-- Sidenav Footer-->
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title">{{ Auth::user()->name }}</div>
        </div>
    </div>
</nav>