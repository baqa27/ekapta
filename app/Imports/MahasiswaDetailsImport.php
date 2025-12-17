<?php

namespace App\Imports;

use App\Models\MahasiswaDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaDetailsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new MahasiswaDetail([
            'nim' => $row['nim'],
            'semester' => $row['semester'],
            'status' => $row['status'],
        ]);
    }
}