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

        $chapters = [
            ['bagian' => 'Bab I', 'is_seminar' => false, 'is_pendadaran' => false],
            ['bagian' => 'Bab II', 'is_seminar' => false, 'is_pendadaran' => false],
            ['bagian' => 'Bab III', 'is_seminar' => false, 'is_pendadaran' => false],
            ['bagian' => 'Bab IV', 'is_seminar' => true, 'is_pendadaran' => false],
            ['bagian' => 'Bab V', 'is_seminar' => false, 'is_pendadaran' => true],
            ['bagian' => 'Produk', 'is_seminar' => true, 'is_pendadaran' => true],
            ['bagian' => 'Full Laporan', 'is_seminar' => true, 'is_pendadaran' => true],
        ];

        // Buat bagian untuk semua prodi
        $prodis = Prodi::all();
        $count = 0;

        foreach ($prodis as $prodi) {
            foreach ($chapters as $chapter) {
                Bagian::firstOrCreate(
                    [
                        'bagian' => $chapter['bagian'],
                        'prodi_id' => $prodi->id,
                    ],
                    [
                        'tahun_masuk' => '2023',
                        'is_seminar' => $chapter['is_seminar'],
                        'is_pendadaran' => $chapter['is_pendadaran'],
                    ]
                );
                $count++;
            }
        }

        $this->command->info('   ✓ ' . $count . ' Bagian bimbingan TA berhasil diseed ke tabel bagians');
    }
}
