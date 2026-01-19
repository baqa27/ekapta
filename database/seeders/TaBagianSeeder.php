<?php

namespace Database\Seeders;

use App\Models\TA\Bagian;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class TaBagianSeeder extends Seeder
{

    /**
     * Seed bagian bimbingan khusus untuk TA (Tugas Akhir)
     * Menggunakan tabel bagians (bukan bagian_kps untuk KP)
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('[TA] Seeding Bagian Bimbingan TA...');

        // LOGIC YANG BENAR (Standard Flow):
        // Sempro: Butuh Bab I, II, III (is_seminar = true)
        // Pendadaran: Butuh Bab IV, V, Produk, Full (is_pendadaran = true) + Bab I-III juga bagian dari skripsi utuh
        $chapters = [
            ['bagian' => 'Bab I', 'is_seminar' => true, 'is_pendadaran' => true],
            ['bagian' => 'Bab II', 'is_seminar' => true, 'is_pendadaran' => true],
            ['bagian' => 'Bab III', 'is_seminar' => true, 'is_pendadaran' => true],
            ['bagian' => 'Bab IV', 'is_seminar' => false, 'is_pendadaran' => true],
            ['bagian' => 'Bab V', 'is_seminar' => false, 'is_pendadaran' => true],
            ['bagian' => 'Produk', 'is_seminar' => false, 'is_pendadaran' => true],
            ['bagian' => 'Full Laporan', 'is_seminar' => false, 'is_pendadaran' => true],
        ];

        // Buat bagian untuk semua prodi
        $prodis = Prodi::all();
        $count = 0;

        foreach ($prodis as $prodi) {
            foreach ($chapters as $chapter) {
                // Gunakan updateOrCreate agar tidak duplicate jika di-seed ulang
                Bagian::updateOrCreate(
                    [
                        'bagian' => $chapter['bagian'],
                        'prodi_id' => $prodi->id,
                    ],
                    [
                        // Support tahun masuk dari 2018 sampai 2029
                        'tahun_masuk' => '2018,2019,2020,2021,2022,2023,2024,2025,2026,2027,2028,2029',
                        'is_seminar' => $chapter['is_seminar'],
                        'is_pendadaran' => $chapter['is_pendadaran'],
                    ]
                );
                $count++;
            }
        }

        $this->command->info('   ✓ ' . $count . ' Bagian bimbingan TA berhasil diseed/diupdate ke tabel bagians');
    }
}
