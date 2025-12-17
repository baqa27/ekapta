<?php

namespace App\Imports;

use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdisImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Prodi([
            'kode' => $row['kode'],
            'namaprodi' => $row['namaprodi'],
            'jenjang' => $row['jenjang'],
            'kodekaprodi' => $row['kodekaprodi'],
            'password' => Hash::make($row['kode']),
        ]);
    }
}