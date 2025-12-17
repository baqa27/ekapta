<?php

namespace App\Helpers;

use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MahasiswaDetail;
use App\Models\Pendaftaran;
use App\Models\Pengajuan;
use App\Models\Prodi;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\Seminar;
use Illuminate\Support\Facades\Auth;

class AppHelper
{
    public function getMahasiswa($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        if ($mahasiswa) {
            return $mahasiswa;
        }
    }

    public function getMahasiswaDetail($nim)
    {
        $mahasiswaDetail = MahasiswaDetail::where('nim', $nim)->first();
        if ($mahasiswaDetail) {
            return $mahasiswaDetail;
        }
    }

    public function getDosen($nidn)
    {
        $dosen = Dosen::where('nidn', $nidn)->first();
        if ($dosen) {
            return $dosen;
        }
    }

    public function getPengajuan($nim)
    {
        $pengajuan = Pengajuan::where('nim', $nim)->first();
        if ($pengajuan) {
            return $pengajuan;
        }
    }

    public function getProdi($kode)
    {
        $prodi = Prodi::where('kode', $kode)->first();
        if ($prodi) {
            return $prodi;
        }
        return null;
    }

    public function getPendaftaran($nim)
    {
        $pendaftaran = Pendaftaran::where('nim', $nim)->first();
        if ($pendaftaran) {
            return $pendaftaran;
        }
    }

    public function getBimbinganIsAcc($mahasiswa_id)
    {
        $bimbingan = Bimbingan::where('mahasiswa_id', $mahasiswa_id)->where('status', 'diterima')->get();
        if ($bimbingan) {
            return $bimbingan;
        }
    }

    public function cekBagianIsAcc($id)
    {
        $bimbingan = Bimbingan::where('id', $id)->where('status', 'diterima')->first();
        if ($bimbingan) {
            return true;
        }
    }

    /**
     * Upload file lampiran with NIM-based folder structure
     * Files will be stored in: storage/app/public/lampirans/{nim}/filename.pdf
     */
    public function uploadLampiran($lampiran, $path, $nim = null, $folder = null)
    {
        if ($lampiran) {
            // If NIM provided, add it to the path for organization
            if ($nim) {
                $path = $path . '/' . $nim;
                if ($folder) {
                    $path = $path . '/' . $folder;
                }
            }

            $lampiranPath = $lampiran->store($path, 'public');
            // Return path without leading slash for consistency
            return $lampiranPath;
        }
    }

    /**
     * Generate correct URL for storage files
     * Based on filesystems.php links config:
     * - public/lampirans → storage/app/public/lampirans
     * - public/images → storage/app/public/images
     * So URLs are /lampirans/... and /images/... (NOT /storage/lampirans/...)
     */
    public function storageUrl($path)
    {
        if (!$path) {
            return null;
        }

        // Remove leading slash if present
        $path = ltrim($path, '/');

        // Handle old paths with full storage/app/public prefix
        // e.g: ekapta-app/storage/app/public/lampirans/file.pdf
        if (str_contains($path, 'storage/app/public/')) {
            $path = substr($path, strpos($path, 'storage/app/public/') + 19);
        }

        // Handle paths that start with 'lampirans/' or 'images/'
        // These are direct symlinks in public folder, so NO /storage/ prefix needed
        if (str_starts_with($path, 'lampirans/') || str_starts_with($path, 'images/')) {
            return url($path);
        }

        // Default: return as-is with url()
        return url($path);
    }


    public function deleteLampiran($lampiran)
    {
        if ($lampiran) {
            // Remove leading slash if present to avoid double slash
            $relativePath = ltrim($lampiran, '/');
            $fullPath = storage_path('app/public/' . $relativePath);

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }

    public function convertImage($base_path)
    {
        $path = base_path($base_path);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $image = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $image;
    }

    /**
     * Cek apakah masa KP sudah expired (6 bulan sejak pendaftaran)
     * Durasi KP: 6 bulan, perpanjangan maksimal 2 kali
     * @param string $date Tanggal pendaftaran di-ACC
     * @return bool|null true jika expired, null jika masih aktif
     */
    public function is_expired_in_one_year($date)
    {
        $status = null;
        // Durasi KP: 6 bulan sejak pendaftaran
        $date_expired = Carbon::parse($date)->addMonthsNoOverflow(6);
        if(now()->gt($date_expired)){
            $status = true;
        }

        return $status;
    }

    /**
     * Alias untuk is_expired_in_one_year - untuk kompatibilitas
     * Mengecek apakah masa KP 6 bulan sudah habis
     */
    public function is_expired_kp($date)
    {
        return $this->is_expired_in_one_year($date);
    }

    public function hitung_nilai_mean($nilai_1, $nilai_2, $nilai_3, $nilai_4)
    {
        return ($nilai_1 + $nilai_2 + $nilai_3 + $nilai_4) / 4;
    }

    public function hitung_nilai_total($nilai_1, $nilai_2, $nilai_3, $nilai_4)
    {
        return $nilai_1 + $nilai_2 + $nilai_3 + $nilai_4;
    }

    public static function parse_date($date){
        $parse_date = Carbon::parse($date);
        $new_date = $parse_date->isoFormat('dddd, D MMMM YYYY H:mm');
        return $new_date.' WIB';
    }

    public static function parse_date_short($date){
        $parse_date = Carbon::parse($date);
        $new_date = $parse_date->isoFormat('dddd, D MMMM YYYY H:mm');
        return $new_date.' WIB';
    }

     public static function parse_date_export($date){
        $parse_date = Carbon::parse($date);
        $new_date = $parse_date->format('d-m-Y');
        return $new_date;
    }

    public static function parse_date_short_surat($date){
        $parse_date = Carbon::parse($date);
        $new_date = $parse_date->isoFormat('D MMMM YYYY');
        return $new_date;
    }

    public static function count_mahasiswa_bimbingan_dosen($dosen, $is_utama = true){
        if ($is_utama) {
            $mahasiswas = $dosen->mahasiswas()->wherePivot('status', 'utama')->whereDoesntHave('jilid')->get();
            // $mahasiswas = $dosen->mahasiswas()->wherePivot('status', 'utama')->get();
        }else{
            $mahasiswas = $dosen->mahasiswas()->wherePivot('status', 'pendamping')->whereDoesntHave('jilid')->get();
            // $mahasiswas = $dosen->mahasiswas()->wherePivot('status', 'pendamping')->get();
        }
        return count($mahasiswas);
    }

    public static function check_bimbingan_is_complete($mahasiswa){
        $bimbingans_acc = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)->get();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $bagians = $prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->get();

        if (count($bimbingans_acc ) - count($bagians) == count($bagians)){
            return true;
        }
        return false;
    }

    public function send_mail($details)
    {
        try {
            Mail::to($details['mail'])->send(new \App\Mail\NotificationMail($details));
        } catch (\Throwable $e) {
            return back()->with('warning','Email notifikasi gagal terkirim');
        }
    }

    public static function hitung_nilai_mahasiswa($ujian_or_seminar)
    {
        $reviews = $ujian_or_seminar->reviews;
        $prodi = Prodi::where('namaprodi', $ujian_or_seminar->mahasiswa->prodi)->first();
        $presentase_nilai = $prodi->presentase_nilai;

        $nilai_penguji = 0;
        $nilai_pembimbing = 0;
        foreach ($reviews as $review) {
            if($review->dosen_status == 'penguji'){
                $nilai_penguji += AppHelper::instance()->hitung_nilai_total($review->nilai_1 * $presentase_nilai->presentase_1 / 100,$review->nilai_2 * $presentase_nilai->presentase_2 / 100, $review->nilai_3 * $presentase_nilai->presentase_3 / 100, $review->nilai_4 * $presentase_nilai->presentase_4 / 100);
            }else if($review->dosen_status == 'pembimbing'){
                $nilai_pembimbing += AppHelper::instance()->hitung_nilai_total($review->nilai_1 * $presentase_nilai->presentase_1 / 100,$review->nilai_2 * $presentase_nilai->presentase_2 / 100, $review->nilai_3 * $presentase_nilai->presentase_3 / 100, $review->nilai_4 * $presentase_nilai->presentase_4 / 100);
            }
        }

        $nilai_dosen_pembimbing = round($nilai_pembimbing / 2, 2);
        $nilai_dosen_penguji = round($nilai_penguji / count($ujian_or_seminar->reviews()->where('dosen_status', 'penguji')->get()), 2);

        $nilai = round(($presentase_nilai->bobot_pembimbing / 100 * $nilai_dosen_pembimbing) + ($presentase_nilai->bobot_penguji / 100 * $nilai_dosen_penguji), 2);

        $nilai_huruf = null;
        if ($nilai > 85) {
            $nilai_huruf = 'A';
        } else if ($nilai > 69) {
            $nilai_huruf = 'B';
        } else if ($nilai > 55) {
            $nilai_huruf = 'C';
        } else if ($nilai > 45) {
            $nilai_huruf = 'D';
        } else if ($nilai > 0) {
            $nilai_huruf = 'E';
        }
        return [
            'nilai_huruf' => $nilai_huruf,
            'nilai' => $nilai,
            'nilai_penguji' => $nilai_dosen_penguji,
            'nilai_pembimbing' => $nilai_dosen_pembimbing,
        ];
    }

    public static function hitung_nilai_kp($seminar)
    {
        $reviews = $seminar->reviews;
        $mahasiswa = $seminar->mahasiswa;
        // Check for Jilid (Final Report) which contains Nilai Instansi
        $jilid = $mahasiswa->jilid;
        $nilai_instansi = $jilid ? $jilid->nilai_instansi : 0;

        $nilai_penguji = 0;
        $nilai_pembimbing = 0;
        $count_penguji = 0;

        foreach ($reviews as $review) {
            // Calculate average of 4 criteria (nilai_1..4)
            // Assuming equal weight for sub-criteria if presentase_1..4 not strictly defined for KP
            // Or use simple average
            $avg_review = ($review->nilai_1 + $review->nilai_2 + $review->nilai_3 + $review->nilai_4) / 4;

            if($review->dosen_status == 'penguji'){
                $nilai_penguji += $avg_review;
                $count_penguji++;
            }else if($review->dosen_status == 'pembimbing'){
                $nilai_pembimbing += $avg_review;
            }
        }

        $nilai_dosen_pembimbing = round($nilai_pembimbing / 2, 2); // Assumes 2 pembimbing or normalized
        if ($count_penguji > 0) {
            $nilai_dosen_penguji = round($nilai_penguji / $count_penguji, 2);
        } else {
            $nilai_dosen_penguji = 0;
        }

        // WEIGHTS for KP (Assumed Default: 35% Pembimbing, 35% Penguji, 30% Instansi)
        // Adjust as per requirements if specified later
        $bobot_pembimbing = 35;
        $bobot_penguji = 35;
        $bobot_instansi = 30;

        $nilai = round(
            ($bobot_pembimbing / 100 * $nilai_dosen_pembimbing) +
            ($bobot_penguji / 100 * $nilai_dosen_penguji) +
            ($bobot_instansi / 100 * $nilai_instansi),
            2
        );

        $nilai_huruf = null;
        if ($nilai > 85) {
            $nilai_huruf = 'A';
        } else if ($nilai > 69) {
            $nilai_huruf = 'B';
        } else if ($nilai > 55) {
            $nilai_huruf = 'C';
        } else if ($nilai > 45) {
            $nilai_huruf = 'D';
        } else if ($nilai > 0) {
            $nilai_huruf = 'E';
        }

        return [
            'nilai_huruf' => $nilai_huruf,
            'nilai' => $nilai,
            'nilai_penguji' => $nilai_dosen_penguji,
            'nilai_pembimbing' => $nilai_dosen_pembimbing,
            'nilai_instansi' => $nilai_instansi
        ];
    }

    /**
     * Cek apakah Seminar KP sudah selesai
     * Untuk KP: tidak ada ujian pendadaran, hanya seminar
     * Syarat: is_lulus = 1 atau tanggal seminar sudah lewat
     * @return bool
     */
    public static function check_ujian_has_done()
    {
        return self::check_seminar_kp_selesai();
    }

    /**
     * Cek apakah Seminar KP sudah selesai
     * @return bool
     */
    public static function check_seminar_kp_selesai()
    {
        $user = Mahasiswa::with(['seminar'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        $seminar = $user->seminar()->where('is_lulus', 1)->first();

        if ($seminar) {
            return true;
        }

        // Fallback: cek berdasarkan tanggal seminar sudah lewat
        $seminar_scheduled = $user->seminar()->where('tanggal_ujian', '!=', null)->first();
        if ($seminar_scheduled) {
            $date_expired = Carbon::parse($seminar_scheduled->tanggal_ujian)->addDay();
            if (now()->gt($date_expired)) {
                return true;
            }
        }

        return false;
    }

    public static function instance()
    {
        return new AppHelper();
    }
}
