# Implementation Plan

## Seminar KP Feature

- [x] 1. Setup Database dan Model






  - [ ] 1.1 Buat migration untuk update tabel seminars
    - Tambah field: no_wa, metode_bayar, status_seminar, sesi_seminar_id, urutan_presentasi, nilai_seminar, catatan_himpunan, catatan_penguji, file_laporan_revisi, bukti_perbaikan, nilai_instansi, file_nilai_instansi, nilai_akhir

    - _Requirements: 2.1-2.6, 5.4, 6.1-6.6, 7.1-7.2_
  - [x] 1.2 Buat migration untuk tabel sesi_seminars


    - Field: tanggal, jam_mulai, jam_selesai, tempat, jumlah_mahasiswa, token_penilaian, is_token_used, token_used_at, dosen_penguji_id, catatan_teknis
    - _Requirements: 4.1, 4.2_


  - [ ] 1.3 Buat migration untuk update tabel himpunans
    - Tambah field: is_pendaftaran_seminar_open (boolean)
    - _Requirements: 1.1, 1.2_


  - [ ] 1.4 Update Model Seminar dengan fillable, casts, constants, dan relationships
    - Tambah status constants, metode bayar options, relationship ke SesiSeminar
    - Tambah method hitungNilaiAkhir()






    - _Requirements: 2.6, 7.1_
  - [x] 1.5 Update Model SesiSeminar dengan fillable, casts, dan relationships

    - Tambah relationship ke Dosen (penguji), seminars
    - Tambah boot method untuk generate token_penilaian UUID
    - Tambah method getLinkPenilaianAttribute(), invalidateToken()
    - _Requirements: 4.2, 4.5, 5.5_




  - [ ] 1.6 Update Model Himpunan dengan method isPendaftaranSeminarOpen()
    - _Requirements: 1.1-1.4_

  - [ ]* 1.7 Write property test untuk token uniqueness
    - **Property 8: Sesi Token Uniqueness**
    - **Validates: Requirements 4.2**


- [ ] 2. Implementasi Fitur Himpunan - Pengaturan Pendaftaran
  - [x] 2.1 Buat HimpunanSeminarController dengan method pengaturan() dan togglePendaftaran()

    - _Requirements: 1.1, 1.2_
  - [ ] 2.2 Buat view himpunan/seminar/pengaturan.blade.php
    - Tampilkan status pendaftaran (Buka/Tutup)
    - Tombol toggle Buka/Tutup Pendaftaran
    - _Requirements: 1.1, 1.2_
  - [ ] 2.3 Tambah routes untuk pengaturan seminar di routes/web.php (himpunan)
    - _Requirements: 1.1, 1.2_
  - [ ]* 2.4 Write property test untuk pendaftaran toggle
    - **Property 1: Pendaftaran Toggle Consistency**
    - **Validates: Requirements 1.1, 1.2**


- [x] 3. Implementasi Fitur Mahasiswa - Pendaftaran Seminar



  - [ ] 3.1 Buat MahasiswaSeminarController dengan method index(), create(), store()
    - Cek isPendaftaranOpen() sebelum tampilkan form
    - Validasi semua field required dan file size max 10MB

    - _Requirements: 1.3, 1.4, 2.1-2.6_
  - [ ] 3.2 Buat view mahasiswa/seminar/index.blade.php (dashboard seminar)
    - Tampilkan status seminar dengan label

    - Tampilkan info sesuai status (catatan, jadwal, nilai)
    - _Requirements: 8.1-8.5_
  - [x] 3.3 Buat view mahasiswa/seminar/create.blade.php (form pendaftaran)

    - Form: no_wa, judul_laporan, file_laporan, file_pengesahan, sertifikat 1-4, metode_bayar, bukti_bayar

    - Tampilkan pesan jika pendaftaran tutup
    - _Requirements: 1.3, 1.4, 2.1-2.5_
  - [ ] 3.4 Tambah routes untuk seminar mahasiswa di routes/web.php
    - _Requirements: 2.1-2.7_
  - [ ]* 3.5 Write property test untuk registration validation
    - **Property 2: Registration Validation Completeness**
    - **Validates: Requirements 2.1-2.5**
  - [ ]* 3.6 Write property test untuk file size validation
    - **Property 3: File Size Validation**
    - **Validates: Requirements 2.2-2.5**



  - [ ]* 3.7 Write property test untuk status initialization
    - **Property 4: Registration Status Initialization**
    - **Validates: Requirements 2.6**


- [ ] 4. Checkpoint - Pastikan semua tests passing
  - Ensure all tests pass, ask the user if questions arise.


- [x] 5. Implementasi Fitur Himpunan - Verifikasi Berkas

  - [ ] 5.1 Tambah method daftarPendaftar(), verifikasi(), prosesVerifikasi() di HimpunanSeminarController
    - Query mahasiswa dengan status menunggu_verifikasi
    - Proses verifikasi: diterima, revisi (dengan catatan), ditolak

    - _Requirements: 3.1-3.7_
  - [ ] 5.2 Buat view himpunan/seminar/daftar-pendaftar.blade.php
    - Tabel mahasiswa dengan status menunggu_verifikasi
    - Link ke detail verifikasi
    - _Requirements: 3.1_
  - [ ] 5.3 Buat view himpunan/seminar/verifikasi.blade.php
    - Tampilkan semua file dengan link view/download

    - Form pilih status (Diterima/Revisi/Ditolak) dan catatan
    - _Requirements: 3.2-3.5_




  - [ ] 5.4 Update MahasiswaSeminarController untuk handle resubmit setelah revisi
    - _Requirements: 3.6, 3.7_
  - [ ] 5.5 Tambah routes untuk verifikasi di routes/web.php
    - _Requirements: 3.1-3.7_

  - [ ]* 5.6 Write property test untuk verification status transition
    - **Property 5: Verification Status Transition**
    - **Validates: Requirements 3.3-3.5**
  - [x]* 5.7 Write property test untuk catatan storage

    - **Property 6: Revisi Catatan Storage**
    - **Validates: Requirements 3.4**

  - [ ]* 5.8 Write property test untuk resubmission status reset
    - **Property 7: Resubmission Status Reset**

    - **Validates: Requirements 3.7**

- [ ] 6. Implementasi Fitur Himpunan - Penjadwalan Seminar
  - [ ] 6.1 Tambah method daftarSesi(), createSesi(), storeSesi(), editSesi(), updateSesi(), assignMahasiswa() di HimpunanSeminarController
    - Generate token_penilaian saat create sesi
    - Dropdown dosen penguji dari tabel dosens
    - _Requirements: 4.1-4.7_
  - [ ] 6.2 Buat view himpunan/seminar/daftar-sesi.blade.php
    - Tabel sesi dengan info tanggal, waktu, tempat, penguji, jumlah mahasiswa
    - Link penilaian yang bisa di-copy
    - _Requirements: 4.5, 4.6_
  - [ ] 6.3 Buat view himpunan/seminar/create-sesi.blade.php
    - Form: tanggal, jam_mulai, jam_selesai, tempat, dosen_penguji_id (dropdown), jumlah_mahasiswa, catatan_teknis
    - _Requirements: 4.1_
  - [ ] 6.4 Buat view himpunan/seminar/assign-mahasiswa.blade.php
    - List mahasiswa dengan status diterima
    - Checkbox assign dan input urutan presentasi


    - _Requirements: 4.3, 4.4_
  - [ ] 6.5 Tambah routes untuk penjadwalan di routes/web.php
    - _Requirements: 4.1-4.7_
  - [ ]* 6.6 Write property test untuk sesi assignment status update
    - **Property 9: Sesi Assignment Status Update**
    - **Validates: Requirements 4.4**
  - [ ]* 6.7 Write property test untuk token link format
    - **Property 10: Token Link Format**
    - **Validates: Requirements 4.5**

- [ ] 7. Checkpoint - Pastikan semua tests passing
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 8. Implementasi Fitur Penilaian Seminar (Tanpa Login)
  - [ ] 8.1 Buat SeminarPenilaianController dengan method index(), submit()
    - Validasi token valid dan belum digunakan
    - Tampilkan info sesi dan list mahasiswa
    - Validasi semua mahasiswa dinilai sebelum submit
    - Invalidate token setelah submit
    - _Requirements: 5.1-5.8_
  - [ ] 8.2 Buat view seminar/penilaian.blade.php
    - Info sesi: tanggal, waktu, lokasi, penguji, catatan teknis
    - Form penilaian per mahasiswa: nama, NIM, judul, urutan, nilai (0-100), status (Diterima/Revisi), catatan
    - Tombol Submit Semua Penilaian
    - _Requirements: 5.1, 5.2_
  - [ ] 8.3 Buat view seminar/penilaian-expired.blade.php
    - Tampilkan pesan token tidak valid/sudah digunakan
    - _Requirements: 5.6_
  - [ ] 8.4 Buat view seminar/penilaian-success.blade.php
    - Tampilkan pesan sukses setelah submit
    - _Requirements: 5.4_
  - [ ] 8.5 Tambah route public untuk penilaian di routes/web.php
    - Route: /penilaian-seminar/{token}
    - _Requirements: 5.1-5.8_
  - [ ]* 8.6 Write property test untuk invalid token rejection
    - **Property 11: Invalid Token Rejection**
    - **Validates: Requirements 5.6**
  - [ ]* 8.7 Write property test untuk incomplete grades rejection
    - **Property 12: Incomplete Grades Rejection**
    - **Validates: Requirements 5.3**
  - [ ]* 8.8 Write property test untuk grade submission persistence
    - **Property 13: Grade Submission Persistence**
    - **Validates: Requirements 5.4**
  - [ ]* 8.9 Write property test untuk token invalidation
    - **Property 14: Token Invalidation After Submission**
    - **Validates: Requirements 5.5**
  - [ ]* 8.10 Write property test untuk grade status transition
    - **Property 15: Grade Status Transition**
    - **Validates: Requirements 5.7, 5.8**

- [ ] 9. Final Checkpoint - Pastikan semua tests passing
  - Ensure all tests pass, ask the user if questions arise.
