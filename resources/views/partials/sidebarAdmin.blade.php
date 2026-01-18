@php
    $active = $active ?? '';
    $module = $module ?? '';
@endphp
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
    <a href="{{ route('dashboard.admin') }}" class="brand-link">
        <img src="https://unsiq.ac.id/img/UNSIQ-bunder.ico" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">EKAPTA Admin</span>
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

                {{-- ============================================== --}}
                {{-- MENU KERJA PRAKTIK --}}
                {{-- ============================================== --}}
                <li class="nav-item has-treeview {{ in_array($active, ['pengajuan-kp', 'pendaftaran-kp', 'bimbingan-kp', 'seminar-kp', 'bimbingan-input-kp', 'pengumpulan-akhir-kp']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>
                            Menu Kerja Praktik
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('kp.pengajuan.admin') }}"
                               class="nav-link {{ $active == 'pengajuan-kp' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pengajuan KP</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kp.pendaftaran.admin') }}"
                               class="nav-link {{ $active == 'pendaftaran-kp' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Validasi Pendaftaran KP</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kp.bimbingan.admin') }}"
                               class="nav-link {{ $active == 'bimbingan-kp' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Progres Bimbingan KP</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kp.bimbingan.admin.input') }}"
                               class="nav-link {{ $active == 'bimbingan-input-kp' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Validasi Bimbingan KP</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kp.seminar.admin') }}"
                               class="nav-link {{ $active == 'seminar-kp' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Seminar KP</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kp.pengumpulan-akhir.index') }}"
                               class="nav-link {{ $active == 'pengumpulan-akhir-kp' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jilid KP</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ============================================== --}}
                {{-- MENU TUGAS AKHIR --}}
                {{-- ============================================== --}}
                <li class="nav-item has-treeview {{ in_array($active, ['pengajuan-ta', 'pendaftaran-ta', 'bimbingan-ta', 'seminar-ta', 'ujian-ta', 'bimbingan-input-ta', 'jilid-ta']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-graduation-cap"></i>
                        <p>
                            Menu Tugas Akhir
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('ta.pengajuan.admin') }}"
                               class="nav-link {{ $active == 'pengajuan-ta' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pengajuan TA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ta.pendaftaran.admin') }}"
                               class="nav-link {{ $active == 'pendaftaran-ta' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Validasi Pendaftaran TA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ta.bimbingan.admin') }}"
                               class="nav-link {{ $active == 'bimbingan-ta' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Progres Bimbingan TA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ta.bimbingan.admin.input') }}"
                               class="nav-link {{ $active == 'bimbingan-input-ta' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Input Bimbingan TA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ta.seminar.admin') }}"
                               class="nav-link {{ $active == 'seminar-ta' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Validasi Seminar TA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ta.ujian.admin') }}"
                               class="nav-link {{ $active == 'ujian-ta' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Validasi Ujian TA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ta.jilid.index') }}"
                               class="nav-link {{ $active == 'jilid-ta' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Validasi Jilid TA</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ============================================== --}}
                {{-- MASTER DATA --}}
                {{-- ============================================== --}}
                <li class="nav-item has-treeview {{ in_array($active, ['prodi', 'mahasiswa', 'dosen', 'fakultas', 'himpunan']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
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
