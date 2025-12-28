<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Auth\Mahasiswa as Authenticatable;

class Mahasiswa extends Authenticatable
{
    use HasFactory;

    protected $hidden = [
        'password',
    ];

    // Status KP keseluruhan
    public const STATUS_KP_BELUM_MULAI = 'belum_mulai';
    public const STATUS_KP_PENGAJUAN_KERANGKA = 'pengajuan_kerangka';
    public const STATUS_KP_PENGAJUAN_JUDUL = 'pengajuan_judul';
    public const STATUS_KP_PENDAFTARAN = 'pendaftaran';
    public const STATUS_KP_PEMBAYARAN = 'pembayaran';
    public const STATUS_KP_BIMBINGAN = 'bimbingan';
    public const STATUS_KP_SEMINAR = 'seminar';
    public const STATUS_KP_PENGUMPULAN_AKHIR = 'pengumpulan_akhir';
    public const STATUS_KP_SELESAI = 'selesai';

    protected $fillable = [
        'nim',
        'nama',
        'thmasuk',
        'prodi',
        'tptlahir',
        'tgllahir',
        'jeniskelamin',
        'kodedosenwali',
        'nik',
        'kelas',
        'email',
        'hp',
        'alamat',
        'password',
        'status_kp',
        'tanggal_mulai_kp',
        'tanggal_selesai_kp',
    ];

    protected $casts = [
        'tanggal_mulai_kp' => 'date',
        'tanggal_selesai_kp' => 'date',
    ];

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_mahasiswas', 'mahasiswa_id', 'dosen_id')
            ->withTimestamps()
            ->withPivot(['status','lampiran']);
    }

    public function bimbingans()
    {
        return $this->hasMany(Bimbingan::class);
    }

    public function bagians()
    {
        return $this->belongsToMany(Bagian::class, 'bimbingans');
    }

    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function seminar()
    {
        return $this->hasOne(Seminar::class);
    }

    /**
     * Pengumpulan Akhir KP (menggunakan model Jilid)
     */
    public function jilid()
    {
        return $this->hasOne(Jilid::class);
    }

    /**
     * Alias untuk jilid() - nama lebih deskriptif
     */
    public function pengumpulanAkhir()
    {
        return $this->hasOne(Jilid::class);
    }

    public function bimbingan_canceleds()
    {
        return $this->hasMany(BimbinganCanceled::class);
    }

    public function seminar_canceleds()
    {
        return $this->hasMany(SeminarCanceled::class);
    }

    /**
     * Get label status KP
     */
    public function getStatusKpLabelAttribute()
    {
        $labels = [
            self::STATUS_KP_BELUM_MULAI => 'Belum Mulai',
            self::STATUS_KP_PENGAJUAN_KERANGKA => 'Pengajuan Kerangka Pikir',
            self::STATUS_KP_PENGAJUAN_JUDUL => 'Pengajuan Judul KP',
            self::STATUS_KP_PENDAFTARAN => 'Pendaftaran KP',
            self::STATUS_KP_PEMBAYARAN => 'Pembayaran',
            self::STATUS_KP_BIMBINGAN => 'Bimbingan KP',
            self::STATUS_KP_SEMINAR => 'Seminar KP',
            self::STATUS_KP_PENGUMPULAN_AKHIR => 'Pengumpulan Akhir',
            self::STATUS_KP_SELESAI => 'Selesai KP',
        ];

        return $labels[$this->status_kp] ?? $this->status_kp;
    }

    /**
     * Dosen pembimbing KP (single pembimbing)
     */
    public function dosenPembimbing()
    {
        return $this->dosens()->where('status', 'pembimbing')->first();
    }
}
