<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminFotokopiSeeder extends Seeder
{
    /**
     * Seed admin fotokopi untuk KP
     */
    public function run(): void
    {
        // Cek apakah sudah ada admin fotokopi
        $exists = Admin::where('kode', 'fotokopi')->first();
        
        if (!$exists) {
            Admin::create([
                'kode' => 'fotokopi',
                'nik' => '0000000002',
                'nama' => 'Fotokopi Fastikom',
                'tgllahir' => '2000-01-01',
                'tptlahir' => 'Wonosobo',
                'alamat' => 'Fastikom UNSIQ',
                'email' => 'fotokopi@unsiq.ac.id',
                'hp' => '081234567890',
                'password' => Hash::make('fotokopi123'),
                'type' => Admin::TYPE_ADMIN_FOTOCOPY, // type = 2
            ]);
            
            $this->command->info('Admin Fotokopi berhasil dibuat!');
            $this->command->info('Username: fotokopi');
            $this->command->info('Password: fotokopi123');
        } else {
            $this->command->info('Admin Fotokopi sudah ada.');
        }
    }
}
