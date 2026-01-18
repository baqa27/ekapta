@extends('kp.layouts.home')

@section('content')

<div class="container-login bg-light pt-5">
    <div class="container">
        <div class="d-flex justify-content-center">
            <h1 class="fw-bold"><a href="{{ route('kp.home') }}"
                    class="text-decoration-none text-dark text-uppercase">Ekapta</a>
            </h1>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <div class="card p-3 col-md-5">
                <h5 class="fw-bold">Login</h5>
                <hr>
                <a href="{{ route('kp.login.mahasiswa') }}" class="btn btn-primary-me mb-3">Login Mahasiswa</a>
                <a href="{{ route('kp.login.prodi') }}" class="btn btn-primary-me mb-3">Login Prodi</a>
                <a href="{{ route('kp.login.dosen') }}" class="btn btn-primary-me mb-3">Login Dosen</a>
                <a href="{{ route('kp.login.admin') }}" class="btn btn-primary-me mb-3">Login Admin</a>
            </div>
        </div>
    </div>
</div>

@endsection




