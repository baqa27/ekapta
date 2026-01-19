<?php

namespace Database\Seeders;

use App\Models\Prodi;
use App\Models\PresentaseNilai;
use Illuminate\Database\Seeder;

class PresentaseNilaiSeeder extends Seeder
{
    /**
     * Seed default presentase nilai for all prodis
     */
    public function run(): void
    {
        $prodis = Prodi::all();

        foreach ($prodis as $prodi) {
            // Check if presentase_nilai already exists for this prodi
            if (!$prodi->presentase_nilai) {
                PresentaseNilai::create([
                    'prodi_id' => $prodi->id,
                    'presentase_1' => 25, // Default 25%
                    'presentase_2' => 25, // Default 25%
                    'presentase_3' => 25, // Default 25%
                    'presentase_4' => 25, // Default 25%
                    'bobot_pembimbing' => 40, // Default 40%
                    'bobot_penguji' => 60, // Default 60%
                ]);
            }
        }

        $this->command->info('Presentase nilai seeded successfully for all prodis!');
    }
}
