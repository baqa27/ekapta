<?php

namespace App\Imports;

use App\Models\Bagian;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BagiansImport implements ToModel, WithHeadingRow
{

    public $prodi;

    public function __construct($prodi)
    {
        $this->prodi = $prodi;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Bagian([
            'prodi_id' => $this->prodi,
            'bagian' => $row['bagian'],
            'tahun_masuk' => $row['tahun_masuk'],
        ]);
    }
}
