<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MahasiswaDetail;
use App\Models\Himpunan;
use App\Models\Pengajuan;
use App\Models\Pendaftaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SeminarKpTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        Admin::firstOrCreate(
            ['kode' => 'admin'],
            [
                'nik' => '3307010101010001',
                'nama' => 'Admin Ekapta KP',
                'tgllahir' => '1990-01-01',
                'tptlahir' => 'Wonosobo',
                'alamat' => 'Wonosobo',
                'email' => 'admin@unsiq.ac.id',
                'hp' => '081234567890',
                'password' => Hash::make('admin123'),
                'type' => Admin::TYPE_SUPER_ADMIN,
            ]
        );

        // 2. Fakultas - FASTIKOM (Fakultas Teknik dan Ilmu Komputer)
        $fakultas = Fakultas::firstOrCreate(
            ['namafakultas' => 'Fakultas Teknik dan Ilmu Komputer'],
            ['image' => null]
        );

        // 3. Prodi - Teknik Informatika & Sistem Informasi
        $prodiTI = Prodi::firstOrCreate(
            ['kode' => 'TI'],
            [
                'namaprodi' => 'Teknik Informatika',
                'jenjang' => 'S1',
                'kodekaprodi' => 'KAPRODI-TI',
                'password' => Hash::make('prodi123'),
                'fakultas_id' => $fakultas->id,
            ]
        );
        // Update jika sudah ada
        $prodiTI->update([
            'namaprodi' => 'Teknik Informatika',
            'fakultas_id' => $fakultas->id,
        ]);

        $prodiSI = Prodi::firstOrCreate(
            ['kode' => 'SI'],
            [
                'namaprodi' => 'Sistem Informasi',
                'jenjang' => 'S1',
                'kodekaprodi' => 'KAPRODI-SI',
                'password' => Hash::make('prodi123'),
                'fakultas_id' => $fakultas->id,
            ]
        );
        $prodiSI->update([
            'namaprodi' => 'Sistem Informasi',
            'fakultas_id' => $fakultas->id,
        ]);


        // 4. Dosen - 4 dosen (2 per prodi)
        $dosenData = [
            // Dosen TI
            ['nidn' => '0601018501', 'nama' => 'Dr. Ahmad Fauzi', 'gelar' => 'M.Kom', 'kodeprodi' => 'TI', 'email' => 'ahmad.fauzi@unsiq.ac.id', 'prodi' => $prodiTI],
            ['nidn' => '0602018502', 'nama' => 'Ir. Budi Santoso', 'gelar' => 'M.T', 'kodeprodi' => 'TI', 'email' => 'budi.santoso@unsiq.ac.id', 'prodi' => $prodiTI],
            // Dosen SI
            ['nidn' => '0603018503', 'nama' => 'Dr. Citra Dewi', 'gelar' => 'M.Kom', 'kodeprodi' => 'SI', 'email' => 'citra.dewi@unsiq.ac.id', 'prodi' => $prodiSI],
            ['nidn' => '0604018504', 'nama' => 'Drs. Dedi Kurniawan', 'gelar' => 'M.Si', 'kodeprodi' => 'SI', 'email' => 'dedi.kurniawan@unsiq.ac.id', 'prodi' => $prodiSI],
        ];

        $dosens = ['TI' => [], 'SI' => []];
        foreach ($dosenData as $data) {
            $dosen = Dosen::updateOrCreate(
                ['nidn' => $data['nidn']],
                [
                    'nik' => '33070101' . substr($data['nidn'], -8),
                    'nama' => $data['nama'],
                    'gelar' => $data['gelar'],
                    'tgllahir' => '1985-01-01',
                    'tptlahir' => 'Wonosobo',
                    'alamat' => 'Wonosobo',
                    'email' => $data['email'],
                    'hp' => '08' . rand(1000000000, 9999999999),
                    'kodeprodi' => $data['kodeprodi'],
                    'password' => Hash::make('dosen123'),
                ]
            );
            $dosens[$data['kodeprodi']][] = $dosen;

            // Attach dosen ke prodi
            if (!$dosen->prodis()->where('prodi_id', $data['prodi']->id)->exists()) {
                $dosen->prodis()->attach($data['prodi']->id, [
                    'kode' => $data['kodeprodi'],
                    'nidn' => $data['nidn'],
                ]);
            }
        }

        // 5. Himpunan - 1 per prodi
        Himpunan::updateOrCreate(
            ['username' => 'himatif'],
            [
                'nama' => 'HIMATIF - Himpunan Mahasiswa Teknik Informatika',
                'email' => 'himatif@unsiq.ac.id',
                'password' => Hash::make('himpunan123'),
                'prodi_id' => $prodiTI->id,
                'is_pendaftaran_seminar_open' => true,
            ]
        );

        Himpunan::updateOrCreate(
            ['username' => 'himasi'],
            [
                'nama' => 'HIMASI - Himpunan Mahasiswa Sistem Informasi',
                'email' => 'himasi@unsiq.ac.id',
                'password' => Hash::make('himpunan123'),
                'prodi_id' => $prodiSI->id,
                'is_pendaftaran_seminar_open' => true,
            ]
        );


        // 6. Mahasiswa SIAP SEMINAR - Sudah punya pengajuan KP & pendaftaran KP diterima
        $mahasiswaSiapSeminar = [
            // Mahasiswa TI
            ['nim' => '20210001', 'nama' => 'Andi Pratama', 'prodi' => 'Teknik Informatika', 'kodeprodi' => 'TI', 'thmasuk' => '2021'],
            ['nim' => '20210002', 'nama' => 'Bintang Cahya', 'prodi' => 'Teknik Informatika', 'kodeprodi' => 'TI', 'thmasuk' => '2021'],
            // Mahasiswa SI
            ['nim' => '20210003', 'nama' => 'Cindy Aulia', 'prodi' => 'Sistem Informasi', 'kodeprodi' => 'SI', 'thmasuk' => '2021'],
            ['nim' => '20210004', 'nama' => 'Dimas Putra', 'prodi' => 'Sistem Informasi', 'kodeprodi' => 'SI', 'thmasuk' => '2021'],
        ];

        foreach ($mahasiswaSiapSeminar as $data) {
            // Get dosen pembimbing
            $dosenPembimbing = $dosens[$data['kodeprodi']][0] ?? null;
            $prodi = $data['kodeprodi'] === 'TI' ? $prodiTI : $prodiSI;

            $mahasiswa = Mahasiswa::updateOrCreate(
                ['nim' => $data['nim']],
                [
                    'nama' => $data['nama'],
                    'thmasuk' => $data['thmasuk'],
                    'prodi' => $data['prodi'],
                    'tptlahir' => 'Wonosobo',
                    'tgllahir' => '2003-01-01',
                    'jeniskelamin' => 'L',
                    'kodedosenwali' => $dosenPembimbing ? $dosenPembimbing->nidn : '-',
                    'nik' => '33070103' . $data['nim'],
                    'kelas' => 'Reguler',
                    'email' => strtolower(str_replace(' ', '.', $data['nama'])) . '@mhs.unsiq.ac.id',
                    'hp' => '08' . rand(1000000000, 9999999999),
                    'alamat' => 'Wonosobo',
                    'password' => Hash::make($data['nim']),
                ]
            );

            // Mahasiswa detail
            MahasiswaDetail::updateOrCreate(
                ['nim' => $data['nim']],
                ['semester' => 7, 'status' => 'Aktif']
            );

            // Assign dosen pembimbing
            if ($dosenPembimbing && !$mahasiswa->dosens()->where('dosen_id', $dosenPembimbing->id)->exists()) {
                $mahasiswa->dosens()->attach($dosenPembimbing->id, [
                    'status' => 'pembimbing',
                    'lampiran' => null,
                ]);
            }

            // Buat pengajuan KP (diterima)
            $pengajuan = Pengajuan::updateOrCreate(
                ['mahasiswa_id' => $mahasiswa->id],
                [
                    'judul' => 'Sistem Informasi ' . $data['nama'] . ' - Studi Kasus PT. XYZ',
                    'deskripsi' => 'Pengembangan sistem informasi untuk PT. XYZ Indonesia',
                    'lokasi_kp' => 'PT. XYZ Indonesia',
                    'alamat_instansi' => 'Jl. Raya No. 123, Wonosobo',
                    'lampiran' => '-',
                    'status' => Pengajuan::DITERIMA,
                    'tanggal_acc' => now()->subDays(60),
                    'prodi_id' => $prodi->id,
                ]
            );

            // Buat pendaftaran KP (diterima) - syarat bisa daftar seminar
            Pendaftaran::updateOrCreate(
                ['mahasiswa_id' => $mahasiswa->id, 'pengajuan_id' => $pengajuan->id],
                [
                    'nomor_pembayaran' => 'PAY-' . $data['nim'],
                    'tanggal_pembayaran' => now()->subDays(35)->format('Y-m-d'),
                    'biaya' => '300000',
                    'lampiran_1' => '-',
                    'lampiran_2' => '-',
                    'lampiran_3' => '-',
                    'lampiran_4' => '-',
                    'lampiran_5' => '-',
                    'status' => 'diterima',
                    'tanggal_acc' => now()->subDays(30),
                ]
            );

            // TIDAK buat seminar - mahasiswa mulai dari awal, belum daftar seminar
        }

        // 7. Mahasiswa FRESH - Belum punya apa-apa (belum pengajuan KP)
        $mahasiswaFresh = [
            // Mahasiswa TI
            ['nim' => '20210005', 'nama' => 'Eka Saputra', 'prodi' => 'Teknik Informatika', 'kodeprodi' => 'TI', 'thmasuk' => '2021'],
            ['nim' => '20210006', 'nama' => 'Fani Rahayu', 'prodi' => 'Teknik Informatika', 'kodeprodi' => 'TI', 'thmasuk' => '2021'],
            // Mahasiswa SI
            ['nim' => '20210007', 'nama' => 'Gilang Ramadhan', 'prodi' => 'Sistem Informasi', 'kodeprodi' => 'SI', 'thmasuk' => '2021'],
            ['nim' => '20210008', 'nama' => 'Hana Permata', 'prodi' => 'Sistem Informasi', 'kodeprodi' => 'SI', 'thmasuk' => '2021'],
        ];

        foreach ($mahasiswaFresh as $data) {
            $dosenPembimbing = $dosens[$data['kodeprodi']][0] ?? null;

            $mahasiswa = Mahasiswa::updateOrCreate(
                ['nim' => $data['nim']],
                [
                    'nama' => $data['nama'],
                    'thmasuk' => $data['thmasuk'],
                    'prodi' => $data['prodi'],
                    'tptlahir' => 'Wonosobo',
                    'tgllahir' => '2003-01-01',
                    'jeniskelamin' => $data['nim'] % 2 == 0 ? 'P' : 'L',
                    'kodedosenwali' => $dosenPembimbing ? $dosenPembimbing->nidn : '-',
                    'nik' => '33070103' . $data['nim'],
                    'kelas' => 'Reguler',
                    'email' => strtolower(str_replace(' ', '.', $data['nama'])) . '@mhs.unsiq.ac.id',
                    'hp' => '08' . rand(1000000000, 9999999999),
                    'alamat' => 'Wonosobo',
                    'password' => Hash::make($data['nim']),
                ]
            );

            MahasiswaDetail::updateOrCreate(
                ['nim' => $data['nim']],
                ['semester' => 7, 'status' => 'Aktif']
            );

            // TIDAK buat pengajuan, pendaftaran, atau seminar - mahasiswa fresh
        }

        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════════════════════════╗');
        $this->command->info('║       DATA TESTING SEMINAR KP BERHASIL DIBUAT                ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ FAKULTAS: FASTIKOM (Fakultas Teknik dan Ilmu Komputer)       ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ ADMIN                                                        ║');
        $this->command->info('║   Login: admin / admin123                                    ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ PRODI                                                        ║');
        $this->command->info('║   - Teknik Informatika (TI) → Login: TI / prodi123           ║');
        $this->command->info('║   - Sistem Informasi (SI)   → Login: SI / prodi123           ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ DOSEN (Login: NIDN / dosen123)                               ║');
        $this->command->info('║   TI: Dr. Ahmad Fauzi (0601018501)                           ║');
        $this->command->info('║       Ir. Budi Santoso (0602018502)                          ║');
        $this->command->info('║   SI: Dr. Citra Dewi (0603018503)                            ║');
        $this->command->info('║       Drs. Dedi Kurniawan (0604018504)                       ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ HIMPUNAN (Login: username / himpunan123)                     ║');
        $this->command->info('║   - HIMATIF (Prodi TI) → Login: himatif / himpunan123        ║');
        $this->command->info('║   - HIMASI (Prodi SI)  → Login: himasi / himpunan123         ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ MAHASISWA (Login: NIM / NIM)                                 ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ SIAP SEMINAR (Pengajuan ✓, Pendaftaran ✓, Seminar ✗)         ║');
        $this->command->info('║   TI: 20210001 Andi Pratama    | 20210002 Bintang Cahya      ║');
        $this->command->info('║   SI: 20210003 Cindy Aulia     | 20210004 Dimas Putra        ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ FRESH (Belum ada apa-apa)                                    ║');
        $this->command->info('║   TI: 20210005 Eka Saputra     | 20210006 Fani Rahayu        ║');
        $this->command->info('║   SI: 20210007 Gilang Ramadhan | 20210008 Hana Permata       ║');
        $this->command->info('╚══════════════════════════════════════════════════════════════╝');
        $this->command->info('');
    }
}
