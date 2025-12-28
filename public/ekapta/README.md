# Template Ekapta - Sistem Kerja Praktek (KP)

link demo : https://febiarifin.github.io/ekapta-template

## Menu aplikasi

```
S = Search
C = Create
R = Read
U = Update
D = Delete
```

### Mahasiswa

- Login
- Dashboard
  - Disajikan alur pengajuan KP, pendaftaran KP, bimbingan KP, seminar KP, dan pengumpulan akhir
- Pengajuan KP
  - Form pengajuan KP (C)
    - Judul KP
    - Deskripsi
    - Upload file
  - Disajikan dalam bentuk tabel (SCRUD)
    - Status : Diterima, revisi, ditolak
  - Mencetak Lembar Persetujuan Pembimbing
- Pendaftaran KP
  - Mengisi formulir pendaftaran (C)
    - Upload beberapa dokumen termasuk lembar persetujuan calon dosen pembimbing yang sudah ditandatangani
  - Disajikan dalam bentuk tabel (SCRUD)
    - Status : Diterima, revisi
- Bimbingan KP
  - Form Memulai bimbingan
    - Memilih bagian bimbingan
    - Upload file
  - Disajikan dalam bentuk tabel (SCRUD)
    - Status : Diterima, revisi
- Seminar KP
  - Disajikan dalam bentuk tabel (SCRUD)
    - Status : Diterima, revisi
  - Mengisi formulir pendaftaran
  - Upload file bimbingan revisi seminar KP
- Pengumpulan Akhir
  - Upload dokumen akhir KP
  - Disajikan rekap nilai
- Logout

### Prodi

- Login
- Dashboard
- Pengajuan KP
  - Disajikan dalam bentuk tabel (SU)
    - Status : Diterima, revisi, ditolak
  - Form Update (U)
    - catatan review
    - status ajuan (Diterima, revisi, ditolak)
  - Menentukan calon pembimbing (CU)
- Bimbingan KP
  - Disajikan dalam bentuk tabel (SR)
    - Status : Diterima, revisi
- Seminar KP
  - Disajikan dalam bentuk tabel (SR)
    - Status : Diterima, revisi
    - Nilai dari dosen pembimbing dan penguji
- Logout

### Dosen

- Login
- Dashboard
- Bimbingan KP
  - Disajikan dalam bentuk tabel (SU)
    - status : Revisi, diterima
  - Form Update (U)
    - catatan review
    - status : Revisi, diterima
    - upload file jika diperlukan
- Seminar KP
  - Disajikan dalam bentuk tabel (SU)
    - status : Revisi, diterima
  - Form Update (U)
    - catatan review
    - status : Revisi, diterima
    - upload file jika diperlukan
  - Input nilai mahasiswa (SU)
    - Dosen penguji memberikan nilai seminar KP
    - Dosen pembimbing memberikan nilai KP (setelah seminar selesai)
- Penilaian Pembimbing
  - Input nilai pembimbing setelah seminar selesai
- Logout

### Admin

- Login
- Dashboard
  - Disajikan list pengajuan KP, pendaftaran KP, seminar KP berdasarkan data terbaru sebanyak 5 data untuk masing-masing
- Validasi Pengajuan KP
  - Disajikan dalam bentuk tabel (R)
- Validasi Pendaftaran KP
  - Disajikan dalam bentuk tabel
  - Revisi disertai upload file berupa berkas pendaftaran
  - Setujui disertai upload file berupa surat tugas bimbingan KP
- Manajemen Bagian Bimbingan Setiap Prodi
  - Disajikan dalam bentuk tabel (SCRUD)
- Validasi Seminar KP
  - Disajikan dalam bentuk tabel
  - Revisi disertai upload file berupa berkas pendaftaran
  - Setujui beserta ploting penguji
  - Mencetak berita acara
- Pengumpulan Akhir
  - Validasi dokumen akhir mahasiswa
- Logout

## Point yang kurang jelas :

1. Login untuk mahasiswa, prodi, dosen, admin apakah dibuat terpisah
2. Isi dokumen ketika mahasiswa melakukan pendaftaran KP setelah Pengajuan KP
3. Isi formulir pendaftaran ketika mahasiswa mendaftar seminar KP
4. Type file ketika upload
5. Berita seminar KP yang dibuat admin dalam bentuk apa, apakah artikel yang bisa dilihat oleh semua mahasiswa, atau hanya untuk mahasiswa yang dituju
