<?php

namespace Database\Seeders;

use App\Models\Bagian;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class KpBagianSeeder extends Seeder
{
    public function run(): void
    {
        $chapters = [
            'Bab I',
            'Bab II',
            'Bab III',
            'Bab IV',
            'Bab V',
            'Produk',
            'Full Laporan'
        ];

        // Buat bagian untuk prodi TI dan SI
        $prodis = Prodi::whereIn('kode', ['TI', 'SI'])->get();

        foreach ($prodis as $prodi) {
            foreach ($chapters as $chapter) {
                Bagian::firstOrCreate(
                    ['bagian' => $chapter, 'prodi_id' => $prodi->id],
                    [
                        'is_seminar' => 0,
                        'is_pendadaran' => 0,
                        'tahun_masuk' => '2021',
                    ]
                );
            }
        }

        $this->command->info('✅ KpBagianSeeder: Bagian bimbingan untuk TI & SI berhasil dibuat');
    }
}
