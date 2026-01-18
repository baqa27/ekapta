<?php

namespace Database\Seeders;

use App\Models\KP\Bagian;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class KpBagianSeeder extends Seeder
{
    /**
     * Seed bagian bimbingan khusus untuk KP
     * Menggunakan tabel bagian_kps (bukan bagians untuk TA)
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('[KP] Seeding Bagian Bimbingan KP...');

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

        $this->command->info('   ✓ ' . $count . ' Bagian bimbingan KP berhasil diseed ke tabel bagian_kps');
    }
}
