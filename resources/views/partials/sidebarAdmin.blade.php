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
                    {{ Auth::guard('admin')->user()->nama }} {{ '(' . Auth::guard('admin')->user()->kode . ')' }}
                </a>
                <a href="{{ route('admin.account') }}" class="dropdown-item">
                    <i class="bi bi-gear mr-2"></i> Pengaturan Akun
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout.admin') }}" class="dropdown-item dropdown-footer bg-danger">Logout <i
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
    <a href="#" class="brand-link">
        <img src="https://unsiq.ac.id/img/UNSIQ-bunder.ico" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Panel Admin</span>
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
                <a href="#" class="d-block">{{ Auth::guard('admin')->user()->nama }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('dashboard.admin') }}"
                       class="nav-link {{ $active == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item
                    @if($active == 'pengajuan' || $active == 'pendaftaran' || $active == 'bimbingan' || $active == 'seminar' || $active == 'pengumpulan-akhir')
                    menu-open
                    @endif">
                    <a href="#" class="nav-link
                    @if($active == 'pengajuan' || $active == 'pendaftaran' || $active == 'bimbingan' || $active == 'seminar' || $active == 'pengumpulan-akhir')
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
                            <a href="{{ route('pengajuan.admin') }}"
                               class="nav-link {{ $active == 'pengajuan' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Pengajuan KP
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pendaftaran.admin') }}"
                               class="nav-link {{ $active == 'pendaftaran' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Validasi Pendaftaran KP
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bimbingan.admin') }}"
                               class="nav-link {{ $active == 'bimbingan' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Progres Bimbingan KP
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('seminar.admin') }}"
                               class="nav-link {{ $active == 'seminar' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Data Seminar KP
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pengumpulan-akhir.index') }}"
                               class="nav-link {{ $active == 'pengumpulan-akhir' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Jilid KP
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item
                    @if($active == 'prodi' || $active == 'mahasiswa' || $active == 'dosen' || $active == 'fakultas' || $active == 'himpunan')
                    menu-open
                    @endif">
                    <a href="#" class="nav-link
                    @if($active == 'prodi' || $active == 'mahasiswa' || $active == 'dosen' || $active == 'fakultas' || $active == 'himpunan')
                    active
                    @endif">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>
                            Master Data
                            <i class="fa fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('prodis') }}" class="nav-link {{ $active == 'prodi' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Prodi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mahasiswas') }}"
                               class="nav-link {{ $active == 'mahasiswa' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mahasiswa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dosens') }}" class="nav-link {{ $active == 'dosen' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dosen</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fakultas') }}"
                               class="nav-link {{ $active == 'fakultas' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fakultas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('himpunans') }}"
                               class="nav-link {{ $active == 'himpunan' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Himpunan</p>
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
