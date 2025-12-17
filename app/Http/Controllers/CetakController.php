<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Pengajuan;
use App\Models\Prodi;
use App\Models\ReviewSeminar;

use App\Models\Seminar;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Str;

class CetakController extends Controller
{

    public function cetakLembarPersetujuanMahasiswa()
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $pengajuan = Pengajuan::where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();
        
        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan belum diterima');
        }
        
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        
        // KP workflow: single dosen with status 'pembimbing'
        // Fallback to 'utama' for legacy data
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        $data = [
            'title' => 'Lembar Persetujuan Pembimbing',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'mahasiswa' => $mahasiswa,
            'pengajuan' => $pengajuan,
            'dosen_pembimbing' => $dosenPembimbing,
            // Legacy support: pass same dosen as dosen_utama for old views
            'dosen_utama' => $dosenPembimbing,
            'dosen_pendamping' => null,
            'prodi' => $prodi,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.lembarPersetujuanPembimbing', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Lembar-Persetujuan-Pembimbing.pdf');
    }

    public function cetakLembarPernyataanKeaslian()
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $data = [
            'title' => 'Lembar Persetujuan Pembimbing',
            'mahasiswa' => $mahasiswa,
        ];
        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.lembarPernyataanKeaslian', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Lembar-Pernyataan-Keaslian.pdf');
    }

    public function cetakSuratTugasBimbingan($pendaftaran)
    {
        $pendaftaran = Pendaftaran::findOrFail($pendaftaran);
        $mahasiswa = $pendaftaran->pengajuan->mahasiswa;
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        
        // KP: 1 dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }
        $dosenUtama = $dosenPembimbing;
        $dosenPendamping = null;

        $qrcode = 'data:image/' . ';base64,' . base64_encode(\QrCode::format('svg')->size(400)->errorCorrection('H')->generate(url('cetak/surat-tugas-bimbingan/' . $pendaftaran->id)));
        $qrcode_bimbingan = 'data:image/' . ';base64,' . base64_encode(\QrCode::format('svg')->size(200)->errorCorrection('H')->generate(url('public/riwayat-bimbingan/' . base64_encode($mahasiswa->id))));

        $tanggal_acc = Carbon::parse($pendaftaran->tanggal_acc);
        $dateLocale = $tanggal_acc->day.' '.$tanggal_acc->monthName.' '.$tanggal_acc->year;

        $dateExpired = Carbon::parse($pendaftaran->tanggal_acc)->addMonthsNoOverflow(6);

        $dekan = ($prodi && $prodi->fakultas) ? $prodi->fakultas->dekans()->where('status', 'active')->first() : null;

        $pendaftarans = Pendaftaran::whereYear('created_at', Carbon::parse($pendaftaran->created_at)->year)->get();
        $no=0;
        $no_urut = 000;
        foreach($pendaftarans as $p){
            $no++;
            if($pendaftaran->id == $p->id){
                $no_urut = sprintf("%03d", $no);
                break;
            }
        }

        $data = [
            'title' => 'Surat Tugas Bimbingan',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'mahasiswa' => $mahasiswa,
            'pendaftaran' => $pendaftaran,
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'prodi' => $prodi,
            'date' =>  $tanggal_acc,
            'dateLocale' => $dateLocale,
            'qr_code' => $qrcode,
            'date_expired' => $dateExpired->day.' '.$dateExpired->monthName.' '.$dateExpired->year,
            'dekan' => $dekan,
            'stempel' => ($prodi && $prodi->fakultas && $prodi->fakultas->image) ? AppHelper::instance()->convertImage('storage/app/public/' . substr($prodi->fakultas->image,31)) : null,
            'ttd_dekan' => ($dekan && $dekan->image) ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dekan->image,31)): null,
            'no_urut' => $no_urut,
             'pengajuan' => $pengajuan,
             'qr_code_bimbingan' => $qrcode_bimbingan,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.suratTugasBimbingan', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Surat-Tugas-Bimbingan.pdf');
    }

    public function cetakSuratTugasBimbinganMahasiswa()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran = Pendaftaran::where('pengajuan_id', $pengajuan->id)->first();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        
        // KP: 1 dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }
        $dosenUtama = $dosenPembimbing;
        $dosenPendamping = null;

        $qrcode = 'data:image/' . ';base64,' . base64_encode(\QrCode::format('svg')->size(200)->errorCorrection('H')->generate(url('cetak/surat-tugas-bimbingan/' . $pendaftaran->id)));
        $qrcode_bimbingan = 'data:image/' . ';base64,' . base64_encode(\QrCode::format('svg')->size(200)->errorCorrection('H')->generate(url('public/riwayat-bimbingan/' . base64_encode($mahasiswa->id))));

        $tanggal_acc = Carbon::parse($pendaftaran->tanggal_acc);
        $dateLocale = $tanggal_acc->day.' '.$tanggal_acc->monthName.' '.$tanggal_acc->year;

        $dateExpired = Carbon::parse($pendaftaran->tanggal_acc)->addMonthsNoOverflow(6);

        $dekan = ($prodi && $prodi->fakultas) ? $prodi->fakultas->dekans()->where('status', 'active')->first() : null;

        $pendaftarans = Pendaftaran::whereYear('created_at', Carbon::parse($pendaftaran->created_at)->year)->get();
        $no=0;
        $no_urut = null;
        foreach($pendaftarans as $p){
            $no++;
            if($pendaftaran->id == $p->id){
                $no_urut = sprintf("%03d", $no);
                break;
            }
        }

        $data = [
            'title' => 'Surat Tugas Bimbingan',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'mahasiswa' => $mahasiswa,
            'pendaftaran' => $pendaftaran,
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'prodi' => $prodi,
            'date' => $tanggal_acc,
            'dateLocale' => $dateLocale,
            'qr_code' => $qrcode,
            'date_expired' => $dateExpired->day.' '.$dateExpired->monthName.' '.$dateExpired->year,
            'dekan' => $dekan,
            'stempel' => ($prodi && $prodi->fakultas && $prodi->fakultas->image) ? AppHelper::instance()->convertImage('storage/app/public/' . substr($prodi->fakultas->image, 31)) : null,
            'ttd_dekan' => ($dekan && $dekan->image) ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dekan->image, 31)) : null,
            'no_urut' => $no_urut,
            'pengajuan' => $pengajuan,
            'qr_code_bimbingan' => $qrcode_bimbingan,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.suratTugasBimbingan', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Surat-Tugas-Bimbingan.pdf');
    }

    public function cetakBeritaAcaraUjianProposal($seminar)
    {
        $seminar = Seminar::findOrFail($seminar);
        if(count($seminar->reviews) == 2){
            return back()->with('warning', 'Silahkan ploting dosen penguji terlebih dahulu');
        }

        $nilai = AppHelper::hitung_nilai_mahasiswa($seminar, 1);

        $seminars_acc = $seminar->reviews()->where('status', ReviewSeminar::DITERIMA)->get();

        $data = [
            'title' => 'BERITA ACARA SEMINAR KP',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'ujian_or_seminar' => $seminar,
            'nilai' => $nilai['nilai_huruf'],
            'is_complete' => count($seminars_acc) >= 4 ? true : null,
            'tanggal_ujian' => Carbon::parse($seminar->tanggal_ujian)->day.' '.Carbon::parse($seminar->tanggal_ujian)->monthName.' '.Carbon::parse($seminar->tanggal_ujian)->year,
            'title_form_nilai' => 'LEMBAR PENILAIAN SEMINAR KP',
            'title_form_revisi' => 'LEMBAR REVISI SEMINAR KP',
            'is_blank' => false,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.berita-acara-seminar', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Berita-Acara-Seminar-KP.pdf');
    }

    public function cetakBeritaAcaraUjianProposalBlank($ujian_or_seminar, $type)
    {
        // Type 1 = Seminar KP, Type 2 tidak dipakai lagi (ujian pendadaran dihapus)
        $ujian_or_seminar = Seminar::findOrFail($ujian_or_seminar);
        
        if(count($ujian_or_seminar->reviews) == 2){
            return back()->with('warning', 'Silahkan ploting dosen penguji terlebih dahulu');
        }

        $data = [
            'title' => 'BERITA ACARA SEMINAR KP',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'ujian_or_seminar' => $ujian_or_seminar,
            'nilai' => null,
            'is_complete' => null,
            'tanggal_ujian' => Carbon::parse($ujian_or_seminar->tanggal_ujian)->day.' '.Carbon::parse($ujian_or_seminar->tanggal_ujian)->monthName.' '.Carbon::parse($ujian_or_seminar->tanggal_ujian)->year,
            'title_form_nilai' => 'LEMBAR PENILAIAN SEMINAR KP',
            'title_form_revisi' => 'LEMBAR REVISI SEMINAR KP',
            'is_blank' => true,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.berita-acara-seminar', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Berita-Acara-Seminar-KP-Blank.pdf');
    }



    public function cetakRiwayatBimbinganMahasiswa(){
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran = Pendaftaran::where('pengajuan_id', $pengajuan->id)->first();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        
        // KP: 1 dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }
        $dosenUtama = $dosenPembimbing;
        $dosenPendamping = null;

        $qrcode = 'data:image/' . ';base64,' . base64_encode(\QrCode::format('svg')->size(200)->errorCorrection('H')->generate(url('public/riwayat-bimbingan/' . base64_encode($mahasiswa->id))));

        $dateLocale = Carbon::parse(now())->day.' '.Carbon::parse(now())->monthName.' '.Carbon::parse(now())->year;

        $dateExpired = Carbon::parse($pendaftaran->tanggal_acc)->addMonthsNoOverflow(6);

        // KP: semua bimbingan dari 1 dosen pembimbing
        $bimbingan_dosen_utama = $dosenPembimbing ? $dosenPembimbing->bimbingans()->with(['revisis','bagian'])->where('mahasiswa_id', $mahasiswa->id)->get() : collect([]);
        $bimbingan_dosen_pendamping = collect([]);

        $pendaftarans = Pendaftaran::whereYear('created_at', Carbon::parse($pendaftaran->created_at)->year)->get();
        $no=0;
        $no_urut = 000;
        foreach($pendaftarans as $p){
            $no++;
            if($pendaftaran->id == $p->id){
                $no_urut = sprintf("%03d", $no);
                break;
            }
        }

        $data = [
            'title' => 'Lembar Bimbingan KP',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'mahasiswa' => $mahasiswa,
            'pendaftaran' => $pendaftaran,
            'pengajuan' => $pengajuan,
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'prodi' => $prodi,
            'date' => now(),
            'dateLocale' => $dateLocale,
            'qr_code' => $qrcode,
            'date_expired' => $dateExpired->day.' '.$dateExpired->monthName.' '.$dateExpired->year,
            'ttd_dosen_utama' => ($dosenUtama && $dosenUtama->ttd) ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dosenUtama->ttd, 31)) : null,
            'ttd_dosen_pendamping' => ($dosenPendamping && $dosenPendamping->ttd) ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dosenPendamping->ttd, 31)) : null,
            'bimbingan_dosen_utama' => $bimbingan_dosen_utama,
            'bimbingan_dosen_pendamping' => $bimbingan_dosen_pendamping,
            'no_urut' => $no_urut,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.lembarBimbinganKP', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Lembar-Bimbingan-KP.pdf');
    }

    public function cetakLembarPersetujuan($type){
        $mahasiswa = Mahasiswa::with(['ujians','pengajuans','dosens'])->where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran = Pendaftaran::where('pengajuan_id', $pengajuan->id)->first();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        $ujian = $mahasiswa->ujians()->whereNotIn('is_lulus', [Ujian::NOT_VALID_LULUS])->where('is_valid', Ujian::VALID_LULUS)->first();
        $reviews  = $ujian->reviews()->where('dosen_status', ReviewUjian::DOSEN_PENGUJI)->with(['dosen'])->get();
        $dosens = [];
        foreach ($reviews as $review) {
            $dosens[] = $review->dosen;
        }

        $data = [
            'title' => $type == 1 ? 'LEMBAR PERSETUJUAN PEMBIMBING' : 'LEMBAR PERSETUJUAN PENGUJI',
            'mahasiswa' => $mahasiswa,
            'pendaftaran' => $pendaftaran,
            'pengajuan' => $pengajuan,
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'prodi' => $prodi,
            'date' => $ujian ? AppHelper::parse_date_short_surat($ujian->tanggal_ujian) : null,
            'ttd_dosen_utama' => $dosenUtama->ttd != null ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dosenUtama->ttd, 31)) : null,
            'ttd_dosen_pendamping' => $dosenPendamping->ttd != null ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dosenPendamping->ttd, 31)) : null,
            'dosens' => $dosens,
            'type' => $type,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.lembar-persetujuan', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream($type == 1 ? 'Lembar-Persetujuan-Pembimbing.pdf' : 'Lembar-Persetujuan-Penguji.pdf');
    }

    public function cetakLembarPengesahan(){
        $mahasiswa = Mahasiswa::with(['ujians','pengajuans','dosens'])->where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran = Pendaftaran::where('pengajuan_id', $pengajuan->id)->first();
        $prodi = Prodi::with(['fakultas', 'fakultas.dekans'])->where('namaprodi', $mahasiswa->prodi)->first();
        $dekan = $prodi->fakultas->dekans()->where('status', 'active')->first();
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        $ujian = $mahasiswa->ujians()->whereNotIn('is_lulus', [Ujian::NOT_VALID_LULUS])->where('is_valid', Ujian::VALID_LULUS)->first();
        $data = [
            'title' => 'LEMBAR PENGESAHAN',
            'mahasiswa' => $mahasiswa,
            'pendaftaran' => $pendaftaran,
            'pengajuan' => $pengajuan,
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'prodi' => AppHelper::instance()->getDosen($prodi->kodekaprodi),
            'date' => $ujian ? AppHelper::parse_date_short_surat($ujian->tanggal_ujian) : null,
            'ttd_dosen_utama' => $dosenUtama->ttd != null ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dosenUtama->ttd, 31)) : null,
            'ttd_dosen_pendamping' => $dosenPendamping->ttd != null ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dosenPendamping->ttd, 31)) : null,
            'dekan' => $dekan,
            'ttd_dekan' => $dekan->image ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dekan->image,31)): null,
            'stempel' => $prodi->fakultas->image ? AppHelper::instance()->convertImage('storage/app/public/' . substr($prodi->fakultas->image,31)) : null,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.lembar-pengesahan', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Lembar-Pengesahan.pdf');
    }
}
