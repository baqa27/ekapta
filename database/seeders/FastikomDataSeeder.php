<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Dekan;
use App\Models\Himpunan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * Seeder untuk data FASTIKOM UNSIQ 2025-2029
 * Berdasarkan struktur organisasi resmi
 */
class FastikomDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== Memulai Setup Data FASTIKOM UNSIQ ===');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // 1. Hapus semua data lama
        $this->cleanAllData();
        
        // 2. Setup Fakultas
        $fakultas = $this->setupFakultas();
        
        // 3. Setup Dekan
        $this->setupDekan($fakultas);
        
        // 4. Setup Admin
        $this->setupAdmin();
        
        // 5. Setup Prodi
        $this->setupProdi($fakultas);
        
        // 6. Setup Dosen per Prodi
        $this->setupDosen();
        
        // 7. Setup Himpunan
        $this->setupHimpunan();
        
        // 8. Setup Admin Fotokopi (type = 2)
        $this->setupAdminFotokopi();
        
        // 9. Setup Mahasiswa Testing
        $this->setupMahasiswa();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // 8. Tampilkan hasil
        $this->showResult();
    }

    private function cleanAllData(): void
    {
        $this->command->info('Membersihkan semua data lama...');
        
        // Hapus pivot table dosen_prodis
        DB::table('dosen_prodis')->truncate();
        DB::table('dosen_mahasiswas')->truncate();
        
        // Hapus semua mahasiswa
        \App\Models\Mahasiswa::query()->delete();
        
        // Hapus semua prodi
        Prodi::query()->delete();
        
        // Hapus semua dosen
        Dosen::query()->delete();
        
        // Hapus semua fakultas
        Fakultas::query()->delete();
        
        // Hapus semua dekan
        Dekan::query()->delete();
        
        // Hapus semua himpunan
        Himpunan::query()->delete();
        
        // Hapus semua admin
        Admin::query()->delete();
    }

    private function setupFakultas(): Fakultas
    {
        $this->command->info('Setup Fakultas...');
        
        return Fakultas::create([
            'namafakultas' => 'Fakultas Teknik dan Ilmu Komputer'
        ]);
    }

    private function setupDekan(Fakultas $fakultas): void
    {
        $this->command->info('Setup Dekan...');
        
        Dekan::create([
            'namadekan' => 'Dr. Nasyiin Faqih',
            'gelar' => 'S.T., M.T., I.P.M.',
            'nidn' => '0601019200',
            'fakultas_id' => $fakultas->id,
            'status' => 'active',
        ]);
        
        $this->command->info('  - Dekan: Dr. Nasyiin Faqih, S.T., M.T., I.P.M.');
    }

    private function setupAdmin(): void
    {
        $this->command->info('Setup Admin...');
        
        Admin::create([
            'kode' => 'admin',
            'nik' => '1234567890',
            'nama' => 'Administrator EKAPTA',
            'tgllahir' => '1990-01-01',
            'tptlahir' => 'Wonosobo',
            'alamat' => 'Wonosobo',
            'email' => 'admin@unsiq.ac.id',
            'hp' => '081234567890',
            'password' => Hash::make('admin123'),
            'type' => Admin::TYPE_SUPER_ADMIN,
        ]);
        
        $this->command->info('  - Super Admin: admin / admin123');
    }

    private function setupAdminFotokopi(): void
    {
        $this->command->info('Setup Admin Fotokopi...');
        
        Admin::create([
            'kode' => 'fotokopi',
            'nik' => '0987654321',
            'nama' => 'Admin Fotokopi FASTIKOM',
            'tgllahir' => '1990-01-01',
            'tptlahir' => 'Wonosobo',
            'alamat' => 'Wonosobo',
            'email' => 'fotokopi@unsiq.ac.id',
            'hp' => '081234567891',
            'password' => Hash::make('fotokopi123'),
            'type' => Admin::TYPE_ADMIN_FOTOCOPY,
        ]);
        
        $this->command->info('  - Admin Fotokopi: fotokopi / fotokopi123');
    }


    private function setupProdi(Fakultas $fakultas): void
    {
        $this->command->info('Setup Prodi...');
        
        $prodiData = [
            ['kode' => 'TS', 'namaprodi' => 'Teknik Sipil', 'jenjang' => 'S1'],
            ['kode' => 'AR', 'namaprodi' => 'Arsitektur', 'jenjang' => 'S1'],
            ['kode' => 'TI', 'namaprodi' => 'Teknik Informatika', 'jenjang' => 'S1'],
            ['kode' => 'MI', 'namaprodi' => 'M. Informatika', 'jenjang' => 'D3'],
            ['kode' => 'TM', 'namaprodi' => 'Teknik Mesin', 'jenjang' => 'S1'],
        ];

        foreach ($prodiData as $data) {
            Prodi::create([
                'kode' => $data['kode'],
                'namaprodi' => $data['namaprodi'],
                'jenjang' => $data['jenjang'],
                'kodekaprodi' => '',
                'password' => Hash::make('prodi123'),
                'fakultas_id' => $fakultas->id,
            ]);
            $this->command->info("  - {$data['namaprodi']} {$data['jenjang']}");
        }
    }

    private function setupDosen(): void
    {
        $this->command->info('Setup Dosen...');
        
        $nidnCounter = 1;
        
        // ==================== TEKNIK SIPIL ====================
        $dosenTS = [
            ['nama' => 'Wiji Lestarini', 'gelar' => 'S.T., M.T.', 'is_kaprodi' => true],
            ['nama' => 'Ir. H. Suharto', 'gelar' => 'M.Eng.'],
            ['nama' => 'Dr. Nasyiin Faqih', 'gelar' => 'S.T., M.T., I.P.M.'],
            ['nama' => 'Adhie Akhmad', 'gelar' => 'S.T., M.Eng.'],
            ['nama' => 'Ashal Abdussalam', 'gelar' => 'S.T., M.T.'],
            ['nama' => 'Joko Adi S', 'gelar' => 'S.T., M.T.'],
            ['nama' => 'Ahmad Alfin', 'gelar' => 'S.T., M.T.'],
        ];
        $this->createDosenForProdi('TS', $dosenTS, $nidnCounter);
        
        // ==================== ARSITEKTUR ====================
        $dosenAR = [
            ['nama' => 'Adinda Septi Hendriani', 'gelar' => 'S.T., M.T.', 'is_kaprodi' => true],
            ['nama' => 'Dr. H. Heri Hermanto', 'gelar' => 'M.T.'],
            ['nama' => 'Prof. Dr. Hermawan', 'gelar' => 'S.T., M.M., M.T.'],
            ['nama' => 'Muafani', 'gelar' => 'S.T., M.T.'],
            ['nama' => 'Ardiyan Adhie Wibowo', 'gelar' => 'S.T., M.T.'],
            ['nama' => 'Dwi Aprilusianto', 'gelar' => 'S.T.'],
        ];
        $this->createDosenForProdi('AR', $dosenAR, $nidnCounter);
        
        // ==================== TEKNIK INFORMATIKA & M. INFORMATIKA (SHARED) ====================
        $dosenTIMI = [
            ['nama' => 'Hidayatus Sibyan', 'gelar' => 'S.Kom., M.Kom.', 'is_kaprodi_ti' => true],
            ['nama' => 'Saifu Rohman', 'gelar' => 'S.Kom., M.Kom.', 'is_kaprodi_mi' => true],
            ['nama' => 'Dian Asmarajati', 'gelar' => 'S.Kom., M.Kom.'],
            ['nama' => 'Erna Dwi Astuti', 'gelar' => 'S.Kom., M.Kom.'],
            ['nama' => 'Adi Suwondo', 'gelar' => 'S.Kom., M.Kom.'],
            ['nama' => 'Nahar Mardiyantoro', 'gelar' => 'S.Kom., M.Kom.'],
            ['nama' => 'M. Fuat Asnawi', 'gelar' => 'S.Kom., M.M., M.Kom.'],
            ['nama' => 'M. Alif Muwafiq B', 'gelar' => 'S.Kom., M.Kom.'],
            ['nama' => 'Muslim Hidayat', 'gelar' => 'S.Kom., M.Kom.'],
            ['nama' => 'Nur Hasanah', 'gelar' => 'S.Kom., M.Kom.'],
            ['nama' => 'Sukowiyono', 'gelar' => 'S.Pd., M.Pd.'],
            ['nama' => 'Iman Ahmad Ihsanuddin', 'gelar' => 'S.Pd.Kom., M.Pd.'],
            ['nama' => 'Nulngafan', 'gelar' => 'S.Kom., M.Kom.'],
            ['nama' => 'Dimas Prasetyo Utomo', 'gelar' => 'M.Kom.'],
            ['nama' => 'Rina Mahmudati', 'gelar' => 'M.Pd.'],
            ['nama' => 'Beta Estri Adiana', 'gelar' => 'M.Eng.'],
        ];
        $this->createDosenForSharedProdi(['TI', 'MI'], $dosenTIMI, $nidnCounter);
        
        // ==================== TEKNIK MESIN ====================
        $dosenTM = [
            ['nama' => 'Dr. Ir. Suwaji', 'gelar' => 'S.M.T., M.M., M.T.', 'is_kaprodi' => true],
            ['nama' => 'Achmad Irfan', 'gelar' => 'S.T., M.Si.'],
            ['nama' => 'Heru Nugroho', 'gelar' => 'S.T., M.Eng.'],
            ['nama' => 'Ir. Sunaryo', 'gelar' => 'M.Pd., M.T.'],
            ['nama' => 'Nur Amin', 'gelar' => 'S.T., M.Eng.'],
            ['nama' => 'M. Furqon Hakim', 'gelar' => 'S.T., M.Si.'],
            ['nama' => 'Leo Van Gunawan', 'gelar' => 'S.T., M.T.'],
            ['nama' => 'Lukman Hakim', 'gelar' => 'S.T., M.T.'],
        ];
        $this->createDosenForProdi('TM', $dosenTM, $nidnCounter);
    }

    private function createDosenForProdi(string $kodeProdi, array $dosenList, int &$nidnCounter): void
    {
        $prodi = Prodi::where('kode', $kodeProdi)->first();
        
        foreach ($dosenList as $data) {
            $nidn = '06010' . str_pad($nidnCounter++, 5, '0', STR_PAD_LEFT);
            
            $dosen = Dosen::create([
                'nidn' => $nidn,
                'nik' => $nidn,
                'nama' => $data['nama'],
                'gelar' => $data['gelar'],
                'tgllahir' => '1980-01-01',
                'tptlahir' => 'Wonosobo',
                'alamat' => 'Wonosobo',
                'email' => strtolower(preg_replace('/[^a-zA-Z]/', '', $data['nama'])) . '@unsiq.ac.id',
                'hp' => '08123456' . str_pad($nidnCounter, 4, '0', STR_PAD_LEFT),
                'kodeprodi' => $kodeProdi,
                'password' => Hash::make('dosen123'),
            ]);
            
            // Attach ke pivot table dosen_prodis
            if ($prodi) {
                $dosen->prodis()->attach($prodi->id, ['kode' => $kodeProdi, 'nidn' => $nidn]);
            }
            
            // Set kaprodi
            if (isset($data['is_kaprodi']) && $data['is_kaprodi'] && $prodi) {
                $prodi->kodekaprodi = $nidn;
                $prodi->save();
                $this->command->info("  [{$kodeProdi}] {$data['nama']}, {$data['gelar']} (KAPRODI)");
            } else {
                $this->command->info("  [{$kodeProdi}] {$data['nama']}, {$data['gelar']}");
            }
        }
    }
    
    private function createDosenForSharedProdi(array $kodeProdis, array $dosenList, int &$nidnCounter): void
    {
        $prodis = Prodi::whereIn('kode', $kodeProdis)->get();
        $prodiTI = $prodis->where('kode', 'TI')->first();
        $prodiMI = $prodis->where('kode', 'MI')->first();
        
        foreach ($dosenList as $data) {
            $nidn = '06010' . str_pad($nidnCounter++, 5, '0', STR_PAD_LEFT);
            
            $dosen = Dosen::create([
                'nidn' => $nidn,
                'nik' => $nidn,
                'nama' => $data['nama'],
                'gelar' => $data['gelar'],
                'tgllahir' => '1980-01-01',
                'tptlahir' => 'Wonosobo',
                'alamat' => 'Wonosobo',
                'email' => strtolower(preg_replace('/[^a-zA-Z]/', '', $data['nama'])) . '@unsiq.ac.id',
                'hp' => '08123456' . str_pad($nidnCounter, 4, '0', STR_PAD_LEFT),
                'kodeprodi' => 'TI', // Default ke TI
                'password' => Hash::make('dosen123'),
            ]);
            
            // Attach ke KEDUA prodi (TI dan MI) via pivot table
            foreach ($prodis as $prodi) {
                $dosen->prodis()->attach($prodi->id, ['kode' => $prodi->kode, 'nidn' => $nidn]);
            }
            
            // Set kaprodi TI
            if (isset($data['is_kaprodi_ti']) && $data['is_kaprodi_ti'] && $prodiTI) {
                $prodiTI->kodekaprodi = $nidn;
                $prodiTI->save();
                $this->command->info("  [TI+MI] {$data['nama']}, {$data['gelar']} (KAPRODI TI)");
            }
            // Set kaprodi MI
            elseif (isset($data['is_kaprodi_mi']) && $data['is_kaprodi_mi'] && $prodiMI) {
                $prodiMI->kodekaprodi = $nidn;
                $prodiMI->save();
                $this->command->info("  [TI+MI] {$data['nama']}, {$data['gelar']} (KAPRODI MI)");
            } else {
                $this->command->info("  [TI+MI] {$data['nama']}, {$data['gelar']}");
            }
        }
    }


    private function setupHimpunan(): void
    {
        $this->command->info('Setup Himpunan per Prodi...');
        
        $himpunanData = [
            ['kode_prodi' => 'TI', 'nama' => 'HIMTI', 'username' => 'himti'],
            ['kode_prodi' => 'MI', 'nama' => 'HIMAMI', 'username' => 'himami'],
            ['kode_prodi' => 'TS', 'nama' => 'HIMATESIP', 'username' => 'himatesip'],
            ['kode_prodi' => 'AR', 'nama' => 'HIMARS', 'username' => 'himars'],
            ['kode_prodi' => 'TM', 'nama' => 'HIMATEM', 'username' => 'himatem'],
        ];
        
        foreach ($himpunanData as $data) {
            $prodi = Prodi::where('kode', $data['kode_prodi'])->first();
            if ($prodi) {
                Himpunan::create([
                    'nama' => $data['nama'],
                    'username' => $data['username'],
                    'password' => Hash::make($data['username'] . '123'),
                    'prodi_id' => $prodi->id,
                ]);
                $this->command->info("  - {$data['nama']} ({$prodi->namaprodi})");
            }
        }
    }
    
    private function setupMahasiswa(): void
    {
        $this->command->info('Setup Mahasiswa Testing...');
        
        // Mahasiswa per prodi untuk testing
        $mahasiswaData = [
            // Teknik Informatika
            ['nim' => '2023010001', 'nama' => 'Ahmad Fauzi', 'prodi' => 'TI', 'thmasuk' => '2023'],
            ['nim' => '2023010002', 'nama' => 'Siti Nurhaliza', 'prodi' => 'TI', 'thmasuk' => '2023'],
            ['nim' => '2023010003', 'nama' => 'Budi Santoso', 'prodi' => 'TI', 'thmasuk' => '2023'],
            // M. Informatika
            ['nim' => '2023020001', 'nama' => 'Dewi Lestari', 'prodi' => 'MI', 'thmasuk' => '2023'],
            ['nim' => '2023020002', 'nama' => 'Eko Prasetyo', 'prodi' => 'MI', 'thmasuk' => '2023'],
            // Teknik Sipil
            ['nim' => '2023030001', 'nama' => 'Fajar Nugroho', 'prodi' => 'TS', 'thmasuk' => '2023'],
            ['nim' => '2023030002', 'nama' => 'Gita Permata', 'prodi' => 'TS', 'thmasuk' => '2023'],
            // Arsitektur
            ['nim' => '2023040001', 'nama' => 'Hendra Wijaya', 'prodi' => 'AR', 'thmasuk' => '2023'],
            ['nim' => '2023040002', 'nama' => 'Indah Sari', 'prodi' => 'AR', 'thmasuk' => '2023'],
            // Teknik Mesin
            ['nim' => '2023050001', 'nama' => 'Joko Susilo', 'prodi' => 'TM', 'thmasuk' => '2023'],
            ['nim' => '2023050002', 'nama' => 'Kartika Dewi', 'prodi' => 'TM', 'thmasuk' => '2023'],
        ];
        
        foreach ($mahasiswaData as $data) {
            \App\Models\Mahasiswa::create([
                'nim' => $data['nim'],
                'nama' => $data['nama'],
                'thmasuk' => $data['thmasuk'],
                'prodi' => $data['prodi'],
                'tptlahir' => 'Wonosobo',
                'tgllahir' => '2005-01-01',
                'jeniskelamin' => 'L',
                'kodedosenwali' => '',
                'nik' => $data['nim'],
                'kelas' => 'A',
                'email' => strtolower(str_replace(' ', '', $data['nama'])) . '@student.unsiq.ac.id',
                'hp' => '08123456' . substr($data['nim'], -4),
                'alamat' => 'Wonosobo',
                'password' => Hash::make('mahasiswa123'),
            ]);
            $this->command->info("  [{$data['prodi']}] {$data['nim']} - {$data['nama']}");
        }
    }

    private function showResult(): void
    {
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('=== HASIL SETUP DATA FASTIKOM UNSIQ ===');
        $this->command->info('========================================');
        
        // Admin
        $this->command->info('');
        $this->command->info('ADMIN:');
        foreach (Admin::all() as $a) {
            $type = $a->type == Admin::TYPE_SUPER_ADMIN ? 'Super Admin' : 'Admin Fotokopi';
            $this->command->info("  {$a->nama} ({$type}) - kode: {$a->kode}");
        }
        
        // Fakultas & Dekan
        $this->command->info('');
        $this->command->info('FAKULTAS & DEKAN:');
        foreach (Fakultas::all() as $f) {
            $dekan = Dekan::where('fakultas_id', $f->id)->where('status', 'active')->first();
            $dekanNama = $dekan ? "{$dekan->namadekan}, {$dekan->gelar}" : 'Belum diset';
            $this->command->info("  {$f->namafakultas}");
            $this->command->info("  Dekan: {$dekanNama}");
        }
        
        // Prodi & Kaprodi
        $this->command->info('');
        $this->command->info('PRODI & KAPRODI:');
        foreach (Prodi::all() as $p) {
            $kaprodi = Dosen::where('nidn', $p->kodekaprodi)->first();
            $kaprodiNama = $kaprodi ? "{$kaprodi->nama}, {$kaprodi->gelar}" : 'Belum diset';
            $dosenCount = $p->dosens()->count();
            $this->command->info("  {$p->namaprodi} {$p->jenjang} ({$dosenCount} dosen)");
            $this->command->info("    Kaprodi: {$kaprodiNama}");
        }
        
        // Info Dosen Shared TI+MI
        $this->command->info('');
        $this->command->info('DOSEN SHARED (TI + MI):');
        $prodiTI = Prodi::where('kode', 'TI')->first();
        $prodiMI = Prodi::where('kode', 'MI')->first();
        if ($prodiTI && $prodiMI) {
            $sharedDosen = $prodiTI->dosens()->whereHas('prodis', function($q) use ($prodiMI) {
                $q->where('prodi_id', $prodiMI->id);
            })->get();
            $this->command->info("  {$sharedDosen->count()} dosen mengajar di TI dan MI");
        }
        
        // Total Dosen
        $this->command->info('');
        $this->command->info("TOTAL DOSEN: " . Dosen::count() . " orang");
        
        // Mahasiswa
        $this->command->info('');
        $this->command->info('MAHASISWA TESTING:');
        foreach (\App\Models\Mahasiswa::all() as $m) {
            $this->command->info("  [{$m->prodi}] {$m->nim} - {$m->nama}");
        }
        $this->command->info("TOTAL MAHASISWA: " . \App\Models\Mahasiswa::count() . " orang");
        
        // Himpunan
        $this->command->info('');
        $this->command->info('HIMPUNAN:');
        foreach (Himpunan::all() as $h) {
            $prodi = $h->prodi ? $h->prodi->namaprodi : 'N/A';
            $this->command->info("  {$h->nama} - {$prodi} (username: {$h->username})");
        }
    }
}
