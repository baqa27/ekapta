<?php

namespace Database\Factories;

use App\Models\Pengajuan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengajuanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = ['diterima', 'review'];
        $judul = ['Lorem ipsum dolor sit amet, consectetur adipiscing', 'Lorem ipsum dolor sit amet, consectetur', 'Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit', 'Lorem ipsum dolor'];
        return [
            'judul' => $judul[rand(0, 4)],
            'nim' => rand(pow(10, 16 - 1), pow(10, 16) - 1),
            'prodi' => 'Teknik Informatika',
            'deskripsi' => $this->faker->paragraph(),
            'lampiran' => 'https://example.com',
            'status' => $status[rand(0, 1)],
        ];
    }
}
