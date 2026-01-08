<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Prodi;

// Update Kaprodi TI dengan NIDN Dr. Ahmad Fauzi
Prodi::where('kode', 'TI')->update(['kodekaprodi' => '0601018501']);

// Update Kaprodi SI dengan NIDN Dr. Citra Dewi
Prodi::where('kode', 'SI')->update(['kodekaprodi' => '0603018503']);

echo "Kode Kaprodi berhasil diupdate!\n";

// Verifikasi
$prodis = Prodi::all();
foreach($prodis as $p) {
    $kaprodi = \App\Helpers\AppHelper::instance()->getDosen($p->kodekaprodi);
    echo "Prodi: " . $p->namaprodi . "\n";
    echo "Kaprodi: " . ($kaprodi ? $kaprodi->nama . ", " . $kaprodi->gelar : "Tidak ditemukan") . "\n";
    echo "NIDN: " . $p->kodekaprodi . "\n";
    echo "-------------------\n";
}
