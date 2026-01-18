<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Seeder ini berisi semua data untuk EKAPTA (TA & KP)
     */
    public function run(): void
    {
        $this->call([
            // Seeder utama - data lengkap FASTIKOM (Dosen, Prodi, Mahasiswa, dll)
            FastikomDataSeeder::class,

            // Seeder bagian KP (tabel bagian_kps)
            KpBagianSeeder::class,
        ]);
    }
}
