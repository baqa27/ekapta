<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Dosen;

echo "=== DAFTAR DOSEN ===\n";
$dosens = Dosen::all();
foreach($dosens as $d) {
    echo "NIDN: " . $d->nidn . " | Nama: " . $d->nama . ", " . $d->gelar . "\n";
}
