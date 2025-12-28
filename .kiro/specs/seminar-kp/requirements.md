1# Requirements Document

## Introduction

Fitur Seminar KP adalah modul untuk mengelola seluruh proses seminar Kerja Praktek (KP) di sistem ekapta12. Fitur ini mencakup pembukaan pendaftaran oleh Himpunan, pengisian formulir oleh mahasiswa, verifikasi berkas, penjadwalan seminar, penilaian oleh dosen penguji (tanpa login via link unik), proses revisi pasca seminar, upload nilai instansi, dan penetapan nilai akhir seminar. Sistem ini mengintegrasikan peran Mahasiswa, Himpunan, Dosen Penguji, dan Prodi.

## Glossary

- **Seminar KP**: Ujian presentasi laporan Kerja Praktek mahasiswa
- **Himpunan**: Organisasi mahasiswa yang mengelola administrasi seminar KP
- **Sesi Seminar**: Satu slot waktu seminar yang berisi beberapa mahasiswa dengan satu dosen penguji
- **Token Penilaian**: Link unik yang digunakan dosen penguji untuk menilai mahasiswa tanpa login
- **Nilai Instansi**: Nilai yang diberikan oleh tempat Praktek mahasiswa
- **Nilai Seminar**: Nilai yang diberikan oleh dosen penguji saat seminar
- **Nilai Akhir**: Gabungan nilai seminar dan nilai instansi dengan bobot tertentu
- **Status Seminar**: Status alur mahasiswa dalam proses seminar (menunggu_verifikasi, diterima, revisi, ditolak, dijadwalkan, selesai_seminar, revisi_pasca, revisi_disetujui, selesai)
- **Revisi Seminar**: Catatan perbaikan dari Himpunan saat verifikasi berkas pendaftaran
- **Revisi Pasca Seminar**: Perbaikan laporan setelah seminar berdasarkan catatan dosen penguji

## Requirements

### Requirement 1: Pembukaan dan Penutupan Pendaftaran Seminar

**User Story:** As a Himpunan, I want to open and close seminar registration, so that I can control when students can register for seminars.

#### Acceptance Criteria

1. WHEN Himpunan clicks "Buka Pendaftaran Seminar" button, THE System SHALL set pendaftaran status to open and allow mahasiswa to access registration form
2. WHEN Himpunan clicks "Tutup Pendaftaran Seminar" button, THE System SHALL set pendaftaran status to closed and disable registration form for mahasiswa
3. WHILE pendaftaran is closed, THE System SHALL display message "Pendaftaran Seminar KP belum dibuka" to mahasiswa and disable all form inputs
4. WHILE pendaftaran is open, THE System SHALL enable all registration form inputs for mahasiswa

### Requirement 2: Formulir Pendaftaran Seminar Mahasiswa

**User Story:** As a Mahasiswa, I want to fill seminar registration form with my data and documents, so that I can register for KP seminar.

#### Acceptance Criteria

1. WHEN mahasiswa submits registration form, THE System SHALL validate that nama lengkap, NIM, and nomor WA are filled
2. WHEN mahasiswa submits registration form, THE System SHALL validate that judul laporan is filled and file laporan PDF is uploaded with maximum size 10 MB
3. WHEN mahasiswa submits registration form, THE System SHALL validate that lembar pengesahan PDF is uploaded with maximum size 10 MB
4. WHEN mahasiswa submits registration form, THE System SHALL validate that 4 sertifikat seminar/pelatihan files are uploaded with maximum size 10 MB each
5. WHEN mahasiswa submits registration form, THE System SHALL validate that metode pembayaran is selected (Cash, DANA, or SeaBank), nominal is 25000, and bukti pembayaran is uploaded with maximum size 10 MB
6. WHEN mahasiswa successfully submits registration, THE System SHALL save data to database and set status_seminar to "menunggu_verifikasi"
7. WHEN registration is saved, THE System SHALL display status notification on mahasiswa dashboard

### Requirement 3: Verifikasi Berkas Pendaftaran oleh Himpunan

**User Story:** As a Himpunan, I want to verify student registration documents, so that I can ensure all requirements are met before scheduling.

#### Acceptance Criteria

1. WHEN Himpunan opens verification menu, THE System SHALL display list of mahasiswa with status_seminar "menunggu_verifikasi"
2. WHEN Himpunan reviews a registration, THE System SHALL display all uploaded files (laporan, pengesahan, sertifikat 1-4, bukti bayar) with view and download links
3. WHEN Himpunan sets status to "Diterima", THE System SHALL update mahasiswa status_seminar to "diterima" and add to scheduling queue
4. WHEN Himpunan sets status to "Revisi" with catatan, THE System SHALL save catatan to catatan_himpunan field and update mahasiswa status_seminar to "revisi"
5. WHEN Himpunan sets status to "Ditolak", THE System SHALL update mahasiswa status_seminar to "ditolak" and require mahasiswa to re-register
6. WHEN mahasiswa has status_seminar "revisi", THE System SHALL display catatan_himpunan and allow mahasiswa to re-upload specific files
7. WHEN mahasiswa resubmits after revision, THE System SHALL update status_seminar to "menunggu_verifikasi" for re-verification

### Requirement 4: Penjadwalan Seminar oleh Himpunan

**User Story:** As a Himpunan, I want to create seminar schedules and assign students, so that seminars can be conducted in organized sessions.

#### Acceptance Criteria

1. WHEN Himpunan creates a new sesi seminar, THE System SHALL require tanggal, jam mulai, jam selesai, tempat (ruangan atau link online), dosen penguji, and jumlah mahasiswa per sesi
2. WHEN Himpunan saves sesi seminar, THE System SHALL generate a unique token_penilaian (UUID) for that session
3. WHEN Himpunan assigns mahasiswa to sesi, THE System SHALL allow setting urutan_presentasi for each mahasiswa
4. WHEN Himpunan assigns mahasiswa to sesi, THE System SHALL update mahasiswa status_seminar to "dijadwalkan" and set sesi_seminar_id
5. WHEN sesi seminar is created, THE System SHALL generate link penilaian using format "/penilaian-seminar/{token_penilaian}" for dosen penguji
6. WHEN Himpunan views sesi detail, THE System SHALL display link penilaian that can be copied and sent to dosen penguji
7. WHEN mahasiswa is assigned to sesi, THE System SHALL display jadwal info (tanggal, waktu, tempat, urutan) on mahasiswa dashboard

### Requirement 5: Penilaian Seminar oleh Dosen Penguji (Tanpa Login)

**User Story:** As a Dosen Penguji, I want to access grading page via unique link without login, so that I can grade all students in one session efficiently.

#### Acceptance Criteria

1. WHEN dosen opens valid token link, THE System SHALL display session info (tanggal, waktu, lokasi, dosen penguji, catatan teknis) and list of all mahasiswa in that session
2. WHEN dosen opens valid token link, THE System SHALL display for each mahasiswa: nama, NIM, judul laporan, urutan presentasi, and input fields for nilai angka (0-100), status hasil (Diterima/Revisi), and catatan penguji opsional
3. WHEN dosen attempts to submit with any mahasiswa not graded, THE System SHALL reject submission and display error message indicating incomplete grades
4. WHEN dosen submits all grades successfully, THE System SHALL save nilai_seminar and catatan to each mahasiswa seminar record
5. WHEN dosen submits all grades successfully, THE System SHALL invalidate token (set is_token_used to true and token_used_at to current time) so link cannot be reused
6. WHEN dosen opens expired or used token link, THE System SHALL display message "Link penilaian tidak valid atau sudah digunakan"
7. WHEN grades are submitted with status "Diterima", THE System SHALL update mahasiswa status_seminar to "selesai_seminar"
8. WHEN grades are submitted with status "Revisi", THE System SHALL update mahasiswa status_seminar to "revisi_pasca" and store catatan penguji

### Requirement 6: Revisi Pasca Seminar

**User Story:** As a Mahasiswa, I want to upload revision documents after seminar, so that I can complete the revision requirements from penguji.

#### Acceptance Criteria

1. WHILE mahasiswa has status_seminar "revisi_pasca", THE System SHALL display catatan penguji and form to upload file_laporan_revisi and bukti_perbaikan
2. WHEN mahasiswa uploads revision files, THE System SHALL save files to database and notify Himpunan for review
3. WHEN Himpunan reviews revision and approves, THE System SHALL update mahasiswa status_seminar to "revisi_disetujui"
4. WHEN Himpunan reviews revision and requests more changes, THE System SHALL keep status_seminar as "revisi_pasca" for mahasiswa to re-upload
5. WHEN mahasiswa has status_seminar "revisi_disetujui", THE System SHALL display form to upload file_nilai_instansi (nilai KP dari instansi)
6. WHEN mahasiswa uploads file_nilai_instansi, THE System SHALL save file and allow input nilai_instansi (angka)

### Requirement 7: Penetapan Nilai Akhir Seminar

**User Story:** As a System, I want to calculate final seminar grades automatically, so that all stakeholders can see the complete seminar result.

#### Acceptance Criteria

1. WHEN nilai_seminar and nilai_instansi are both available, THE System SHALL calculate nilai_akhir using formula: (nilai_seminar * 0.6) + (nilai_instansi * 0.4)
2. WHEN nilai_akhir is calculated, THE System SHALL save nilai_akhir to mahasiswa seminar record
3. WHEN nilai_akhir is set and all requirements completed, THE System SHALL update mahasiswa status_seminar to "selesai"
4. WHEN Himpunan views rekap nilai, THE System SHALL display all mahasiswa with their nilai_seminar, nilai_instansi, and nilai_akhir
5. WHEN Prodi views nilai seminar, THE System SHALL display complete list of mahasiswa with all nilai components

### Requirement 8: Dashboard Status Seminar Mahasiswa

**User Story:** As a Mahasiswa, I want to see my seminar status and information on dashboard, so that I can track my progress.

#### Acceptance Criteria

1. WHEN mahasiswa opens seminar page, THE System SHALL display current status_seminar with appropriate label (Menunggu Verifikasi, Diterima, Revisi, Ditolak, Dijadwalkan, Selesai Seminar, Revisi Pasca Seminar, Revisi Disetujui, Selesai)
2. WHEN mahasiswa has status_seminar "revisi", THE System SHALL display catatan_himpunan
3. WHEN mahasiswa has status_seminar "dijadwalkan", THE System SHALL display jadwal info (tanggal, waktu, tempat, urutan presentasi, dosen penguji)
4. WHEN mahasiswa has status_seminar "revisi_pasca", THE System SHALL display catatan penguji from seminar
5. WHEN mahasiswa has status_seminar "selesai", THE System SHALL display nilai_seminar, nilai_instansi, and nilai_akhir
