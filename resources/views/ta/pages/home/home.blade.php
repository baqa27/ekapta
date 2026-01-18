@extends('ta.layouts.home')

@section('content')

@include('ta.partials.navbar')

<!-- Content Home -->
<div class="container" id="home">
    <div class="row container-home">
        <div class="col-md-6 container-text-home">
            <h1 class="fw-bolder">{{ config('app.name') }}</h1>
            <p class="text-secondary fs-5 mt-4">{{ env("APP_SYNONYM") }}</p>
            <a href="#alur" class="btn btn-primary-me mt-5">Mulai Sekarang</a>
        </div>
        <div class="col-md-6 container-image-home">
            <img src="{{ asset('ekapta') }}/assets/img/img-1.png" alt="Ekapta" class="image-home">
        </div>
    </div>
</div>
<!-- End Content Home -->

<!-- Content Tentang -->
<div class="bg-light">
    <div class="container text-center container-tentang" id="tentang">
        <h3 class="fw-bolder">Apa itu Ekapta?</h3>
        <div class="d-flex justify-content-center">
            <hr class="col-md-3">
        </div>
        <div class="d-flex justify-content-center">
            <p class="mt-4 col-md-6 text-secondary fs-5">{{ env("APP_DESCRIPTION") }}</p>
        </div>
    </div>
</div>
<!-- End Content Tentang -->

<!-- Content Alur -->
<div class="container text-center container-alur" id="alur">
    <h3 class="fw-bolder">Alur Ekapta</h3>
    <div class="d-flex justify-content-center">
        <hr class="col-md-3">
    </div>
    <div class="row justify-content-center mt-5">
        <div class="col-md-2 mb-3">
            <a href="#" data-bs-toggle="modal" data-bs-target="#pengajuanModal" class="text-decoration-none text-dark">
                <div class="card" style="min-height: 12rem;">
                    <div class="car-body text-center">
                        <img src=" {{ asset('ekapta') }}/assets/img/img-2.png" width="150" alt="Ekapta">
                        <p class="fs-6 fw-semibold">Pengajuan</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 mb-3">
            <a href="#" data-bs-toggle="modal" data-bs-target="#pendaftaranModal"
                class="text-decoration-none text-dark">
                <div class="card" style="min-height: 12rem;">
                    <div class="car-body text-center">
                        <img src=" {{ asset('ekapta') }}/assets/img/img-8.png" width="160" height="128" alt="Ekapta">
                        <p class="fs-6 fw-semibold">Pendaftaran</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 mb-3">
            <a href="#" data-bs-toggle="modal" data-bs-target="#bimbinganModal" class="text-decoration-none text-dark">
                <div class="card" style="min-height: 12rem;">
                    <div class="car-body text-center">
                        <img src=" {{ asset('ekapta') }}/assets/img/img-6.png" width="160" height="128" alt="Ekapta">
                        <p class="fs-6 fw-semibold">Bimbingan</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 mb-3">
            <a href="#" data-bs-toggle="modal" data-bs-target="#seminarModal" class="text-decoration-none text-dark">
                <div class="card" style="min-height: 12rem;">
                    <div class="car-body text-center">
                        <img src=" {{ asset('ekapta') }}/assets/img/img-7.png" width="160" height="128" alt="Ekapta">
                        <p class="fs-6 fw-semibold">Seminar Proposal</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 mb-3">
            <a href="#" data-bs-toggle="modal" data-bs-target="#ujianModal" class="text-decoration-none text-dark">
                <div class="card" style="min-height: 12rem;">
                    <div class="car-body text-center">
                        <img src=" {{ asset('ekapta') }}/assets/img/img-5.png" width="150" alt="Ekapta">
                        <p class="fs-6 fw-semibold">Ujian Pendadaran</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Modal Pengajuan-->
<div class="modal fade" id="pengajuanModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <div>
                    <h5 class="modal-title" id="exampleModalToggleLabel">Pengajuan</h5>
                </div>
                <div>
                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <ul>
                    <li>
                        Mahasiswa mengajukan judul TA dengan mengisi form ajuan diantaranya: Judul TA, deskripsi,
                        dan upload file
                    </li>
                    <li>
                        Jika status ajuan Diterima maka bisa melanjutkan ke proses pendaftaran. Jika status Revisi
                        maka dapat dapat merevisi ajuan. Jika status Ditolak maka harus membuat ajuan baru.
                    </li>
                    <li>
                        Setelah status ajuan diterima, mahasiswa dapat mencetak Lembar Persetujuan Pembimbing yang
                        harus ditandatangani oleh calon dosen pembimbing, kemudian diupload di Form Pendaftaran
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Modal Pendaftaran -->
<div class="modal fade" id="pendaftaranModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <div>
                    <h5 class="modal-title" id="exampleModalToggleLabel">Pendaftaran</h5>
                </div>
                <div>
                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <ul>
                    <li>
                        Pendaftaran hanya bisa dilakukan Jika status ajuan diterima
                    </li>
                    <li>
                        Mahasiswa mengisi formular pendaftaran dan mengupload beberapa dokumen termasuk lembar
                        persetujuan calon dosen pembimbing yang sudah ditandatangani
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Modal Bimbingan -->
<div class="modal fade" id="bimbinganModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <div>
                    <h5 class="modal-title" id="exampleModalToggleLabel">Bimbingan</h5>
                </div>
                <div>
                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <ul>
                    <li>
                        Mahaiswa memulai bimbingan dengan memilih bagian (missal Bab I) kemudian mahasiswa upload
                        file bimbingan Bab I
                    </li>
                </ul>
                <p class="fw-semibold" style="margin-left: 18px;">Setelah direview Dosen :</p>
                <ul>
                    <li>
                        Jika status dari dosen revisi, maka mahasiswisa memperbaiki laporannya dan bisa upload
                        Kembali sampai status dari dosen Diterima
                    </li>
                    <li>
                        Setelah Bab I diterima, maka bisa melanjutkan bimbingan ke bab II dst
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Modal Seminar Proposan -->
<div class="modal fade" id="seminarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <div>
                    <h5 class="modal-title" id="exampleModalToggleLabel">Seminar Proposal</h5>
                </div>
                <div>
                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <ul>
                    <li>
                        Syarat untuk mendaftar seminar proposal, harus sudah acc bab I sampai bab III
                    </li>
                    <li>
                        Mahasiswa mendaftara seminar dengan mengisi form pendaftaran
                    </li>
                </ul>
                <p class="fw-semibold" style="margin-left: 18px;">Setelah Seminar Proposal :</p>
                <ul>
                    <li>
                        Mahasiswa upload bimbingan revisi seminar proposal ditujukan ke dosen penguji
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Modal Ujian -->
<div class="modal fade" id="ujianModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <div>
                    <h5 class="modal-title" id="exampleModalToggleLabel">Ujian Pendadaran</h5>
                </div>
                <div>
                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <ul>
                    <li>
                        Syarat untuk mendaftar ujian pendadaran, harus sudah acc semua bab, produk, dan artikel
                    </li>
                    <li>
                        Mahasiswa mendaftar ujian pendadaran dengan mengisi form pendaftaran
                    </li>
                </ul>
                <p class="fw-semibold" style="margin-left: 18px;">Setelah Ujian Pendadaran :</p>
                <ul>
                    <li>
                        Mahasiswa upload bimbingan revisi seminar proposal ditujukan ke dosen penguji
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@include('ta.partials.footer')

@endsection




