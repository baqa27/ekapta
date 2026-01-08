<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Prodi;

echo "=== DAFTAR PRODI ===\n";
$prodis = Prodi::all();
foreach($prodis as $p) {
    echo "ID: " . $p->id . "\n";
    echo "Kode: " . $p->kode . "\n";
    echo "Nama: " . $p->namaprodi . "\n";
    echo "Kode Kaprodi: " . $p->kodekaprodi . "\n";
    echo "-------------------\n";
}
