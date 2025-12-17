<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jilid extends Model
{
    use HasFactory;

    public const JILID_REVIEW = 1;
    public const JILID_REVISI = 2;
    public const JILID_VALID = 3;
    public const JILID_SELESAI = 4;

    public const JILID_COMPLETED = 1;

    protected $fillable = [
        'mahasiswa_id',
        'total_pembayaran',
        'status',
        'laporan_pdf',
        'laporan_word',
        'lembar_pengesahan',
        'lembar_keaslian',
        'lembar_persetujuan_penguji',
        'lembar_persetujuan_pembimbing',
        'lembar_bimbingan',
        'lembar_revisi',
        'berita_acara',
        'link_project',
        'catatan',
        'artikel',
        'is_completed',
        'panduan',
        'lampiran',
        'bukti_nilai_instansi',
        'nilai_instansi',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function revisis()
    {
        return $this->hasMany(RevisiJilid::class);
    }

}
