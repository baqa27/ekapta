<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bolder text-uppercase" href="#home">Ekapta</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto text-right">
                <li class="nav-item fw-bold active rounded">
                    <a class="nav-link" aria-current="page" href="#home">Home</a>
                </li>
                <li class="nav-item fw-bold">
                    <a class="nav-link" href="#tentang">Tentang</a>
                </li>
                <li class="nav-item fw-bold">
                    <a class="nav-link" href="#alur">Alur</a>
                </li>
                <li class="nav-item fw-bold">
                    <a class="nav-link" href="#kontak">Kontak</a>
                </li>
            </ul>
            <hr class="border-nav m-3">
            <div class="text-right container-login-register">
                @if(Auth::guard('mahasiswa')->user() ||Auth::guard('dosen')->user() || Auth::guard('prodi')->user() ||
                Auth::guard('admin')->user())
                <a href="{{ route('back.dashboard') }}" class="btn btn-primary-me btn-login">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary-me btn-login">Login</a>
                @endif
            </div>
        </div>
    </div>
</nav>
<!-- End Navbar -->
