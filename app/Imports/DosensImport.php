<?php

namespace App\Imports;

use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DosensImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Dosen([
            'nidn' => $row['nidn'],
            'nik'  => $row['nik'],
            'nama' => $row['nama'],
            'gelar'  => $row['gelar'],
            'tgllahir'  => $row['tgllahir'],
            'tptlahir'  => $row['tptlahir'],
            'alamat'  => $row['alamat'],
            'email'  => $row['email'],
            'hp'  => $row['hp'],
            'kodeprodi'  => $row['kodeprodi'],
            'password'  => Hash::make($row['nidn']),
        ]);
    }
}
