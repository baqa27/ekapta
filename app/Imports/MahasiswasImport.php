<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswasImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Mahasiswa([
            'nim' => $row['nim'],
            'nama' => $row['nama'],
            'thmasuk' => $row['thmasuk'],
            'prodi' => $row['prodi'],
            'tptlahir' => $row['tptlahir'],
            'tgllahir' => $row['tgllahir'],
            'jeniskelamin' => $row['jeniskelamin'],
            'kodedosenwali' => $row['kodedosenwali'],
            'nik' => $row['nik'],
            'kelas' => $row['kelas'],
            'email' => $row['email'],
            'hp' => $row['hp'],
            'alamat' => $row['alamat'],
            'password' => Hash::make($row['nim']),
        ]);
    }
}