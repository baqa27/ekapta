# Logika Bimbingan Tugas Akhir (TA)

## Konsep Dasar
- Mahasiswa memiliki 2 pembimbing: **Utama** dan **Pendamping**
- Setiap pembimbing memiliki set bimbingan yang **independen**
- Mahasiswa bisa submit bimbingan ke kedua pembimbing secara **bersamaan** untuk bab yang sama

## Status Bimbingan
1. **NULL** (belum submit) - Mahasiswa belum mengupload dokumen
2. **review** - Dosen sedang mereview dokumen yang disubmit
3. **revisi** - Dosen meminta revisi
4. **diterima** - Dosen meng-ACC bimbingan

## Logika Tombol Submit (Konsisten untuk Semua Bab)

### 1. Status = `review`
- **Tampilkan**: Tombol Detail
- **Tidak tampilkan**: Tombol Submit
- **Alasan**: Dosen sedang mereview, mahasiswa harus menunggu

### 2. Status = `revisi`
- **Tampilkan**: Tombol Detail + Tombol Submit
- **Alasan**: Mahasiswa harus submit ulang dengan perbaikan

### 3. Status = `diterima`
- **Tampilkan**: Tombol Detail
- **Tidak tampilkan**: Tombol Submit
- **Alasan**: Bimbingan sudah ACC, tidak perlu submit lagi

### 4. Status = `null` (Belum Submit)
Tombol Submit muncul jika memenuhi salah satu kondisi:

#### Kondisi A: Bab Pertama
```php
if ($no == 1) {
    // Selalu bisa submit
    $canSubmit = true;
}
```

#### Kondisi B: Bab Selanjutnya
```php
elseif (count(getBimbinganIsAcc($mahasiswa_id)) >= 1) {
    // Ada minimal 1 bimbingan yang sudah ACC (dari pembimbing manapun)
    
    if (!cekBagianIsAcc($nim, $pembimbing)) {
        // Tidak ada bimbingan yang sedang review/revisi untuk pembimbing ini
        $canSubmit = true;
    }
}
```

## Helper Functions

### `getBimbinganIsAcc($mahasiswa_id)`
- **Return**: Collection bimbingan dengan status `diterima`
- **Scope**: SEMUA bimbingan (utama + pendamping)
- **Kegunaan**: Cek apakah mahasiswa sudah pernah ACC minimal 1 bimbingan

### `cekBagianIsAcc($nim, $pembimbing)`
- **Parameter**: 
  - `$nim`: NIM mahasiswa
  - `$pembimbing`: 'utama' atau 'pendamping'
- **Return**: 
  - `true` = Ada bimbingan yang sedang review/revisi (TIDAK BISA submit bab berikutnya)
  - `false` = Tidak ada yang review/revisi (BISA submit bab berikutnya)
- **Scope**: Hanya bimbingan untuk pembimbing yang dispesifikkan
- **Kegunaan**: Cek apakah pembimbing ini sedang mereview bimbingan lain

## Contoh Skenario

### Skenario 1: Mahasiswa Baru Mulai
- Bab I Utama: NULL → **Tombol Submit muncul** (bab pertama)
- Bab I Pendamping: NULL → **Tombol Submit muncul** (bab pertama)
- Bab II dst: NULL → **Tombol Submit TIDAK muncul** (belum ada yang ACC)

### Skenario 2: Bab I Sudah ACC
- Bab I Utama: diterima
- Bab I Pendamping: diterima
- Bab II Utama: NULL → **Tombol Submit muncul** (ada ACC & tidak ada review/revisi di utama)
- Bab II Pendamping: NULL → **Tombol Submit muncul** (ada ACC & tidak ada review/revisi di pendamping)

### Skenario 3: Bab II Utama Sedang Review
- Bab I Utama: diterima
- Bab I Pendamping: diterima
- Bab II Utama: review
- Bab II Pendamping: NULL → **Tombol Submit muncul** (tidak ada review/revisi di pendamping)
- Bab III Utama: NULL → **Tombol Submit TIDAK muncul** (ada review di utama)
- Bab III Pendamping: NULL → **Tombol Submit muncul** (tidak ada review/revisi di pendamping)

### Skenario 4: Submit Bersamaan
- Bab I-IV sudah ACC untuk utama dan pendamping
- Bab V Utama: NULL → **Tombol Submit muncul**
- Bab V Pendamping: NULL → **Tombol Submit muncul**
- Mahasiswa bisa submit Bab V ke kedua pembimbing secara bersamaan

## Keuntungan Logika Ini

1. **Konsisten**: Semua bab menggunakan logika yang sama
2. **Independen**: Pembimbing utama dan pendamping tidak saling memblokir
3. **Fleksibel**: Mahasiswa bisa submit ke kedua pembimbing bersamaan
4. **Sederhana**: Hanya 2 kondisi utama (bab pertama atau ada ACC + tidak ada review)
5. **Tidak ada circular dependency**: Tidak ada kondisi yang menunggu dirinya sendiri ACC

## Catatan Penting

- **is_seminar** dan **is_pendadaran** di tabel `bagians` TIDAK mempengaruhi logika submit button
- Flag tersebut hanya digunakan untuk:
  - `is_seminar = 1`: Bagian yang harus ACC sebelum bisa daftar Seminar
  - `is_pendadaran = 1`: Bagian yang harus ACC sebelum bisa daftar Ujian Pendadaran
- Logika submit button HANYA bergantung pada:
  1. Apakah bab pertama?
  2. Apakah ada bimbingan yang sudah ACC?
  3. Apakah ada bimbingan yang sedang review/revisi untuk pembimbing ini?
