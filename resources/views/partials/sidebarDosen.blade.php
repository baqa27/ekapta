<nav class="main-header navbar navbar-expand navbar-white navbar-light sticky-top">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    {{ Auth::guard('dosen')->user()->nama }} {{ '('.Auth::guard('dosen')->user()->nidn.')' }}
                </a>
                <a href="{{ route('dosen.account') }}" class="dropdown-item">
                    <i class="bi bi-gear mr-2"></i> Pengaturan Akun
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout.dosen') }}" class="dropdown-item dropdown-footer bg-danger">Logout <i
                        class="bi bi-box-arrow-right ml-2"></i></a>
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
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="https://unsiq.ac.id/img/UNSIQ-bunder.ico" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Panel Dosen</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('ekapta') }}/adminLTE/dist/img/default-profile.png" class="img-circle elevation-2"
                     alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::guard('dosen')->user()->nama.',
                    '.Auth::guard('dosen')->user()->gelar }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('dashboard.dosen') }}"
                       class="nav-link {{ $active=='dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item
                 @if($active == 'bimbingan' || $active == 'seminar' || $active=='bimbingan-progress' || $active=='penilaian')
                    menu-open
                    @endif">
                    <a href="#" class="nav-link
                    @if($active == 'bimbingan' || $active == 'seminar' || $active=='bimbingan-progress' || $active=='penilaian')
                    active
                    @endif">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Menu Kerja Praktek
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('bimbingan.dosen.progress') }}"
                               class="nav-link {{ $active=='bimbingan-progress' ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Progress Bimbingan KP
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('bimbingan.dosen') }}"
                               class="nav-link {{ $active=='bimbingan' ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Review Bimbingan KP
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('seminar.dosen') }}"
                               class="nav-link {{ $active=='seminar' ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Review Seminar KP
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('penilaian.pembimbing.index') }}"
                               class="nav-link {{ $active=='penilaian' ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Penilaian Pembimbing
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
