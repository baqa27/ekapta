@extends('layouts.home')

@section('content')

<div class="container-login bg-light pt-5">
    <div class="container">
        <div class="d-flex justify-content-center">
            <h1 class="fw-bold"><a href="{{ route('home') }}"
                    class="text-decoration-none text-dark text-uppercase">EKAPTA</a>
            </h1>
        </div>
        <p class="text-center text-muted">Sistem Elektronik Kerja Praktik dan Tugas Akhir</p>
        <div class="d-flex justify-content-center mt-3">
            <div class="card p-3 col-md-5">
                <h5 class="fw-bold">Login</h5>
                <hr>
                <a href="{{ route('login.mahasiswa') }}" class="btn btn-primary-me mb-3">
                    <i class="bi bi-person-fill me-2"></i> Login Mahasiswa
                </a>
                <a href="{{ route('login.prodi') }}" class="btn btn-primary-me mb-3">
                    <i class="bi bi-building me-2"></i> Login Prodi
                </a>
                <a href="{{ route('login.dosen') }}" class="btn btn-primary-me mb-3">
                    <i class="bi bi-person-badge me-2"></i> Login Dosen
                </a>
                <a href="{{ route('login.admin') }}" class="btn btn-primary-me mb-3">
                    <i class="bi bi-shield-lock me-2"></i> Login Admin
                </a>
                <a href="{{ route('login.himpunan') }}" class="btn btn-primary-me mb-3">
                    <i class="bi bi-people me-2"></i> Login Himpunan
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
