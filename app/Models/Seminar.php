<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    use HasFactory;

    // Nama tabel dengan suffix _kp
    protected $table = 'seminar_kps';

    // Status is_valid (verifikasi himpunan)
    public const REVIEW = 0;
    public const DITERIMA = 1;
    public const REVISI = 2;
    public const DITOLAK = 3;

    public const VALID = 1;
    public const NOT_VALID = 0;

    // Status seminar (alur keseluruhan) - KP tidak ada revisi pasca seminar
    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';
    public const STATUS_DITERIMA = 'diterima';           // Berkas diterima himpunan
    public const STATUS_REVISI = 'revisi';               // Revisi berkas pendaftaran
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_DIJADWALKAN = 'dijadwalkan';     // Sudah dapat jadwal seminar
    public const STATUS_SELESAI_SEMINAR = 'selesai_seminar'; // Seminar selesai, tinggal upload nilai instansi
    public const STATUS_SELESAI = 'selesai';             // Semua selesai, nilai akhir sudah ada

    // Metode pembayaran
    public const METODE_CASH = 'Cash';
    public const METODE_DANA = 'DANA';
    public const METODE_SEABANK = 'SeaBank';

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'no_wa',
        'lampiran_1',
        'lampiran_2',
        'lampiran_3',
        'lampiran_4',
        'jumlah_bayar',
        'metode_bayar',
        'nomor_pembayaran',
        'lampiran_proposal',
        'link_akses_produk', // Link akses produk KP
        'dokumen_penilaian', // Dokumen penilaian opsional
        'is_valid',
        'is_lulus',
        'tanggal_acc',
        'tanggal_selesai',
        'tanggal_ujian',
        'tempat_ujian',
        'judul_laporan',
        'file_laporan',
        'file_pengesahan',
        'bukti_bayar',
        'file_laporan_revisi',
        'bukti_perbaikan',
        'nilai_instansi',
        'file_nilai_instansi',
        'nilai_seminar',
        'nilai_akhir',
        'status_seminar',
        'catatan_himpunan',
        'catatan_penguji',
        'sesi_seminar_id',
        'urutan_presentasi',
    ];

    protected $casts = [
        'tanggal_acc' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_ujian' => 'datetime',
        'nilai_instansi' => 'decimal:2',
        'nilai_seminar' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
    ];

    public function revisis()
    {
        return $this->hasMany(RevisiSeminar::class);
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function reviews()
    {
        return $this->hasMany(ReviewSeminar::class);
    }

    public function sesiSeminar()
    {
        return $this->belongsTo(SesiSeminar::class);
    }

    public function dosenPenguji()
    {
        return $this->belongsTo(Dosen::class, 'dosen_penguji_id');
    }

    public static function getMetodeBayarOptions()
    {
        return [
            self::METODE_CASH,
            self::METODE_DANA,
            self::METODE_SEABANK,
        ];
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_MENUNGGU_VERIFIKASI => 'Menunggu Verifikasi',
            self::STATUS_DITERIMA => 'Diterima',
            self::STATUS_REVISI => 'Revisi Berkas',
            self::STATUS_DITOLAK => 'Ditolak',
            self::STATUS_DIJADWALKAN => 'Dijadwalkan',
            self::STATUS_SELESAI_SEMINAR => 'Selesai Seminar',
            self::STATUS_SELESAI => 'Selesai KP',
        ];

        return $labels[$this->status_seminar] ?? $this->status_seminar;
    }

    /**
     * Hitung nilai akhir seminar (sementara)
     * Nilai akhir KP final dihitung di Pengumpulan Akhir (Jilid)
     * dengan bobot: Pembimbing 35%, Penguji 35%, Instansi 30%
     */
    public function hitungNilaiAkhir()
    {
        if ($this->nilai_seminar && $this->nilai_instansi) {
            // Nilai akhir sementara di seminar (untuk display)
            // Nilai final akan dihitung di Jilid dengan nilai pembimbing
            $this->nilai_akhir = ($this->nilai_seminar * 0.6) + ($this->nilai_instansi * 0.4);
            $this->save();
        }
    }

    /**
     * Get nilai huruf dari nilai akhir
     */
    public function getNilaiHurufAttribute()
    {
        if (!$this->nilai_akhir) return null;
        
        if ($this->nilai_akhir > 85) return 'A';
        if ($this->nilai_akhir > 69) return 'B';
        if ($this->nilai_akhir > 55) return 'C';
        if ($this->nilai_akhir > 45) return 'D';
        return 'E';
    }
}
