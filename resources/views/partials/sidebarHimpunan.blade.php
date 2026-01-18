@php
    $active = $active ?? '';
    $module = $module ?? '';
@endphp
<nav class="main-header navbar navbar-expand navbar-white navbar-light sticky-top">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    {{ Auth::guard('himpunan')->user()->nama }} ({{ Auth::guard('himpunan')->user()->username }})
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout.himpunan') }}" class="dropdown-item dropdown-footer bg-danger">Logout <i class="bi bi-box-arrow-right ml-2"></i></a>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <img src="https://unsiq.ac.id/img/UNSIQ-bunder.ico" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">EKAPTA Himpunan</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('ekapta') }}/adminLTE/dist/img/default-profile.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::guard('himpunan')->user()->nama }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{ route('kp.dashboard.himpunan') }}" class="nav-link {{ $active == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- ============================================== --}}
                {{-- MENU KERJA PRAKTIK (HIMPUNAN HANYA KP) --}}
                {{-- ============================================== --}}
                <li class="nav-item has-treeview {{ in_array($active, ['seminar-kp', 'jadwal-kp', 'rekap-kp']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array($active, ['seminar-kp', 'jadwal-kp', 'rekap-kp']) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>
                            Menu Kerja Praktik
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('kp.seminar.himpunan') }}" class="nav-link {{ $active == 'seminar-kp' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Verifikasi Pendaftaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kp.jadwal.himpunan') }}" class="nav-link {{ $active == 'jadwal-kp' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Penjadwalan Seminar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kp.seminar.himpunan.rekap') }}" class="nav-link {{ $active == 'rekap-kp' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rekap Seminar KP</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('kp.payment.himpunan') }}" class="nav-link {{ $active == 'payment' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>Pengaturan Pembayaran</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
