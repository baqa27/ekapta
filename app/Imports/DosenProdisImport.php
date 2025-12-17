<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\DosenProdi;
use App\Models\Prodi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DosenProdisImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $dosen = Dosen::where('nidn', $row['nidn'])->first();
        $prodi = Prodi::where('kode', $row['kodeprodi'])->first();

        return new DosenProdi([
            'dosen_id' => $dosen->id,
            'prodi_id' => $prodi->id,
            'nidn' => $row['nidn'],
            'kode' => $row['kodeprodi'],
        ]);
    }
}
