<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class MahasiswaSultanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mahasiswa::create([
            'nim' => '2023150108',
            'nama' => 'Muhammad Sultan Baqa',
            'thmasuk' => '2023',
            'prodi' => '150',
            'tptlahir' => '-',
            'tgllahir' => '2005-01-01',
            'jeniskelamin' => 'L',
            'kodedosenwali' => '-',
            'nik' => '-',
            'kelas' => '-',
            'hp' => '-',
            'alamat' => '-',
            'email' => 'sultanbaqa05@gmail.com',
            'password' => bcrypt('2023150108'),
            'status_kp' => 'belum_mulai',
        ]);
    }
}
