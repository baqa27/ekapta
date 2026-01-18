<?php

namespace App\Imports;

use App\Models\Dekan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DekansImport implements ToModel, WithHeadingRow
{
    public function __construct($fakultas)
    {
        $this->fakultas = $fakultas;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Dekan([
            'nidn' => $row['nidn'],
            'namadekan' => $row['namadekan'],
            'gelar' => $row['gelar'],
            'dari' => $row['dari'] != null ? $row['dari']: '',
            'sampai' => $row['sampai'] != null ? $row['sampai'] : '',
            'fakultas_id' => $this->fakultas,
        ]);
    }
}
