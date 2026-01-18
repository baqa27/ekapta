<?php

namespace App\Http\Controllers\KP;

use App\Helpers\AppHelper;

// Import KP Models
use App\Models\KP\Jilid;
use App\Models\KP\Pendaftaran;
use App\Models\KP\RevisiJilid;

// Import Shared Models
use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Prodi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PengumpulanAkhirController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('kp.pages.pengumpulan-akhir.admin-index', [
            'title' => 'Jilid KP',
            'active' => 'pengumpulan-akhir-kp',
            'sidebar' => Auth::guard('admin')->user()->type == Admin::TYPE_SUPER_ADMIN ? 'partials.sidebarAdmin' : null,
            // Super Admin bisa lihat semua, Fotokopi hanya lihat yang VALID dan SELESAI
            'jilids' => Auth::guard('admin')->user()->type == Admin::TYPE_SUPER_ADMIN
                ? Jilid::orderBy('created_at','desc')->get()
                : Jilid::orderBy('created_at','desc')->whereIn('status', [Jilid::JILID_VALID, Jilid::JILID_SELESAI])->get(),
        ]);
    }

    /**
     * Halaman index pengumpulan akhir untuk mahasiswa
     * Syarat: Seminar KP sudah selesai
     */
    public function mahasiswaIndex()
    {
        $mahasiswa = Mahasiswa::with(['jilid', 'seminar'])->findOrFail(Auth::guard('mahasiswa')->user()->id);

        // Cek tahapan: Seminar harus selesai dulu
        if (!AppHelper::canAccessPengumpulanAkhir($mahasiswa)) {
            return redirect()->route('kp.seminar.mahasiswa')->with('warning', 'Selesaikan tahap Seminar KP terlebih dahulu. Anda harus lulus seminar untuk mengakses Pengumpulan Akhir.');
        }

        // Hitung nilai KP
        $nilai_kp = null;
        if ($mahasiswa->seminar) {
            $nilai_kp = AppHelper::hitung_nilai_kp($mahasiswa->seminar);
        }

        return view('kp.pages.pengumpulan-akhir.index', [
            'title' => 'Pengumpulan Akhir KP',
            'active' => 'pengumpulan-akhir-kp',
            'jilids' => $mahasiswa->jilidKP ? [$mahasiswa->jilidKP] : [],
            'jilid' => $mahasiswa->jilidKP,
            'nilai_kp' => $nilai_kp,
        ]);
    }

    /**
     * Form Pengumpulan Akhir KP
     * Syarat: Seminar KP sudah selesai (is_lulus = 1)
     */
    public function create()
    {
        $mahasiswa = Mahasiswa::with(['seminarKP','pendaftaransKP'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        $pendaftaran = $mahasiswa->pendaftaransKP()->where('status', Pendaftaran::DITERIMA)->first();

        // Cek tahapan: Seminar harus selesai dulu
        if (!AppHelper::canAccessPengumpulanAkhir($mahasiswa)) {
            return redirect()->route('kp.seminar.mahasiswa')->with('warning', 'Selesaikan tahap Seminar KP terlebih dahulu. Anda harus lulus seminar untuk mengakses Pengumpulan Akhir.');
        }

        if ($mahasiswa->jilidKP) {
            return redirect()->route('kp.pengumpulan-akhir.mahasiswa')->with('warning','Sudah melakukan pengajuan pengumpulan akhir KP. Tunggu validasi oleh Admin');
        }

        return view('kp.pages.pengumpulan-akhir.create', [
            'title' => 'Submit Pengumpulan Akhir KP',
            'active' => 'pengumpulan-akhir-kp',
            'pendaftaran' => $pendaftaran,
        ]);
    }

    public function store(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        if ($mahasiswa->jilidKP) {
            return back();
        }
        $validatedData = $request->validate([
            'laporan_pdf' => [Rule::requiredIf(function() use($request){
                return !empty($request->laporan_pdf);
            }), 'mimes:pdf', 'max:5000'],
            'laporan_word' => [Rule::requiredIf(function() use($request){
                return !empty($request->laporan_word);
            }), 'mimes:docx', 'max:5000'],
            'lembar_pengesahan' => ['required', 'mimes:pdf', 'max:500'],
            'lembar_keaslian' => ['required', 'mimes:pdf', 'max:500'],
            'lembar_persetujuan_penguji' => ['required', 'mimes:pdf', 'max:500'],
            'lembar_persetujuan_pembimbing' => ['required', 'mimes:pdf', 'max:500'],
            'lembar_bimbingan' => ['required', 'mimes:pdf', 'max:500'],
            'lembar_revisi' => ['required', 'mimes:pdf', 'max:500'],
            'artikel' => [Rule::requiredIf(function() use($request){
                return !empty($request->artikel);
            }), 'mimes:docx', 'max:5000'],
            'berita_acara' => ['required', 'mimes:pdf', 'max:1000'],
            'lampiran' => [Rule::requiredIf(function() use($request){
                return !empty($request->lampiran);
            }), 'mimes:pdf', 'max:1000'],
            'panduan' => [Rule::requiredIf(function() use($request){
                return !empty($request->panduan);
            }), 'mimes:docx', 'max:5000'],
            'bukti_nilai_instansi' => ['required', 'mimes:pdf', 'max:1000'],
        ]);

        if ($request->laporan_pdf) {
            $validatedData['laporan_pdf'] = AppHelper::instance()->uploadLampiran($request->laporan_pdf, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        } else {
            $validatedData['laporan_pdf'] = $request->laporan_link_pdf;
        }
        if ($request->laporan_word) {
            $validatedData['laporan_word'] = AppHelper::instance()->uploadLampiran($request->laporan_word, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        } else {
            $validatedData['laporan_word'] = $request->laporan_link;
        }
        $validatedData['lembar_pengesahan'] = AppHelper::instance()->uploadLampiran($request->lembar_pengesahan, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        $validatedData['lembar_keaslian'] = AppHelper::instance()->uploadLampiran($request->lembar_keaslian, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        $validatedData['lembar_persetujuan_penguji'] = AppHelper::instance()->uploadLampiran($request->lembar_persetujuan_penguji, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        $validatedData['lembar_persetujuan_pembimbing'] = AppHelper::instance()->uploadLampiran($request->lembar_persetujuan_pembimbing, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        $validatedData['lembar_bimbingan'] = AppHelper::instance()->uploadLampiran($request->lembar_bimbingan, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        $validatedData['lembar_revisi'] = AppHelper::instance()->uploadLampiran($request->lembar_revisi, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        if ($request->artikel) {
            $validatedData['artikel'] = AppHelper::instance()->uploadLampiran($request->artikel, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        } else {
            $validatedData['artikel'] = $request->artikel_link;
        }
        if ($request->berita_acara) {
            $validatedData['berita_acara'] = AppHelper::instance()->uploadLampiran($request->berita_acara, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->panduan) {
            $validatedData['panduan'] = AppHelper::instance()->uploadLampiran($request->panduan, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        } else {
            $validatedData['panduan'] = $request->panduan_link;
        }
        if ($request->lampiran) {
            $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        }
        $validatedData['bukti_nilai_instansi'] = AppHelper::instance()->uploadLampiran($request->bukti_nilai_instansi, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        $validatedData['mahasiswa_id'] = $mahasiswa->id;
        $validatedData['status'] = Jilid::JILID_REVIEW;
        $validatedData['link_project'] = $request->link_project;

        Jilid::create($validatedData);
        return redirect()->route('kp.pengumpulan-akhir.mahasiswa')->with('success', 'Pengajuan pengumpulan akhir berhasil. Silahkan tunggu validasi dari Admin');
    }

    public function detail($id)
    {
        $jilid = Jilid::with(['mahasiswa','revisis'])->findOrFail($id);
        $mahasiswa = $jilid->mahasiswa()->with(['bimbingans', 'seminar.sesiSeminar.dosenPenguji', 'seminar.dosenPenguji'])->first();
        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();

        // Hitung nilai KP jika ada seminar
        $nilai_kp = null;
        $review_pembimbing = null;
        $review_penguji = null;
        if ($mahasiswa->seminar) {
            $nilai_kp = AppHelper::hitung_nilai_kp($mahasiswa->seminar);
            $review_pembimbing = $mahasiswa->seminar->reviews()->where('dosen_status', 'pembimbing')->first();
            $review_penguji = $mahasiswa->seminar->reviews()->where('dosen_status', 'penguji')->first();
        }

        return view('kp.pages.pengumpulan-akhir.detail', [
            'title' => 'Detail Jilid KP',
            'sidebar' => Auth::guard('admin')->user()->type == Admin::TYPE_SUPER_ADMIN ? 'partials.sidebarAdmin' : null,
            'active' => Auth::guard('admin')->user()->type == Admin::TYPE_SUPER_ADMIN ? 'pengumpulan-akhir' : null,
            'jilid' => $jilid,
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'is_admin' => true,
            'revisis' => $jilid->revisis()->paginate(5),
            'nilai_kp' => $nilai_kp,
            'review_pembimbing' => $review_pembimbing,
            'review_penguji' => $review_penguji,
        ]);
    }

    public function detailMahasiswa($id)
    {
        $jilid = Jilid::with(['mahasiswa','revisis'])->findOrFail($id);
        $mahasiswa = $jilid->mahasiswa()->with(['bimbingans', 'seminar.sesiSeminar.dosenPenguji', 'seminar.dosenPenguji'])->first();
        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();

        if (Auth::guard('mahasiswa')->user()->id != $mahasiswa->id) {
            return back();
        }

        // Hitung nilai KP jika ada seminar
        $nilai_kp = null;
        $review_pembimbing = null;
        $review_penguji = null;
        if ($mahasiswa->seminar) {
            $nilai_kp = AppHelper::hitung_nilai_kp($mahasiswa->seminar);
            $review_pembimbing = $mahasiswa->seminar->reviews()->where('dosen_status', 'pembimbing')->first();
            $review_penguji = $mahasiswa->seminar->reviews()->where('dosen_status', 'penguji')->first();
        }

        return view('kp.pages.pengumpulan-akhir.detail', [
            'title' => 'Detail Jilid KP',
            'active' => 'pengumpulan-akhir-kp',
            'jilid' => $jilid,
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'is_admin' => false,
            'revisis' => $jilid->revisis()->paginate(5),
            'nilai_kp' => $nilai_kp,
            'review_pembimbing' => $review_pembimbing,
            'review_penguji' => $review_penguji,
        ]);
    }

    public function edit($id)
    {
        $jilid = Jilid::with(['mahasiswa','revisis'])->findOrFail($id);

        if ($jilid->status != Jilid::JILID_REVISI) {
            return back();
        } elseif (Auth::guard('mahasiswa')->user()->id != $jilid->mahasiswa->id) {
            return back();
        }

        return view('kp.pages.pengumpulan-akhir.edit', [
            'title' => 'Revisi Pengumpulan Akhir KP',
            'active' => 'pengumpulan-akhir-kp',
            'jilid' => $jilid,
            'pendaftaran' => $jilid->mahasiswa->pendaftarans()->where('status', Pendaftaran::DITERIMA)->first(),
            'revisis' => $jilid->revisis,
        ]);
    }


    public function update(Request $request, $id)
    {
        $jilid = Jilid::with(['mahasiswa'])->where('id', $id)->first();
        if ($jilid->status != Jilid::JILID_REVISI) {
            return back();
        }

        $validatedData = $request->validate([
            'laporan_pdf' => [Rule::requiredIf(fn() => !empty($request->laporan_pdf)), 'mimes:pdf', 'max:5000'],
            'laporan_word' => [Rule::requiredIf(fn() => !empty($request->laporan_word)), 'mimes:docx', 'max:5000'],
            'lembar_pengesahan' => [Rule::requiredIf(fn() => !empty($request->lembar_pengesahan)), 'mimes:pdf', 'max:500'],
            'lembar_keaslian' => [Rule::requiredIf(fn() => !empty($request->lembar_keaslian)), 'mimes:pdf', 'max:500'],
            'lembar_persetujuan_pembimbing' => [Rule::requiredIf(fn() => !empty($request->lembar_persetujuan_pembimbing)), 'mimes:pdf', 'max:500'],
            'lembar_persetujuan_penguji' => [Rule::requiredIf(fn() => !empty($request->lembar_persetujuan_penguji)), 'mimes:pdf', 'max:500'],
            'lembar_bimbingan' => [Rule::requiredIf(fn() => !empty($request->lembar_bimbingan)), 'mimes:pdf', 'max:500'],
            'lembar_revisi' => [Rule::requiredIf(fn() => !empty($request->lembar_revisi)), 'mimes:pdf', 'max:500'],
            'artikel' => [Rule::requiredIf(fn() => !empty($request->artikel)), 'mimes:docx', 'max:5000'],
            'berita_acara' => [Rule::requiredIf(fn() => !empty($request->berita_acara)), 'mimes:pdf', 'max:1000'],
            'panduan' => [Rule::requiredIf(fn() => !empty($request->panduan)), 'mimes:docx', 'max:5000'],
            'lampiran' => [Rule::requiredIf(fn() => !empty($request->lampiran)), 'mimes:pdf', 'max:1000'],
            'bukti_nilai_instansi' => [Rule::requiredIf(fn() => !empty($request->bukti_nilai_instansi)), 'mimes:pdf', 'max:1000'],
        ]);

        $mahasiswa = $jilid->mahasiswa;

        if ($request->laporan_pdf) {
            AppHelper::instance()->deleteLampiran($jilid->laporan_pdf);
            $validatedData['laporan_pdf'] = AppHelper::instance()->uploadLampiran($request->laporan_pdf, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        } elseif ($request->laporan_link_pdf) {
            AppHelper::instance()->deleteLampiran($jilid->laporan_pdf);
            $validatedData['laporan_pdf'] = $request->laporan_link_pdf;
        }
        if ($request->laporan_word) {
            AppHelper::instance()->deleteLampiran($jilid->laporan_word);
            $validatedData['laporan_word'] = AppHelper::instance()->uploadLampiran($request->laporan_word, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        } elseif ($request->laporan_link) {
            AppHelper::instance()->deleteLampiran($jilid->laporan_word);
            $validatedData['laporan_word'] = $request->laporan_link;
        }
        if ($request->lembar_pengesahan) {
            AppHelper::instance()->deleteLampiran($jilid->lembar_pengesahan);
            $validatedData['lembar_pengesahan'] = AppHelper::instance()->uploadLampiran($request->lembar_pengesahan, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->lembar_keaslian) {
            AppHelper::instance()->deleteLampiran($jilid->lembar_keaslian);
            $validatedData['lembar_keaslian'] = AppHelper::instance()->uploadLampiran($request->lembar_keaslian, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->lembar_persetujuan_pembimbing) {
            AppHelper::instance()->deleteLampiran($jilid->lembar_persetujuan_pembimbing);
            $validatedData['lembar_persetujuan_pembimbing'] = AppHelper::instance()->uploadLampiran($request->lembar_persetujuan_pembimbing, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->lembar_persetujuan_penguji) {
            AppHelper::instance()->deleteLampiran($jilid->lembar_persetujuan_penguji);
            $validatedData['lembar_persetujuan_penguji'] = AppHelper::instance()->uploadLampiran($request->lembar_persetujuan_penguji, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->lembar_bimbingan) {
            AppHelper::instance()->deleteLampiran($jilid->lembar_bimbingan);
            $validatedData['lembar_bimbingan'] = AppHelper::instance()->uploadLampiran($request->lembar_bimbingan, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->lembar_revisi) {
            AppHelper::instance()->deleteLampiran($jilid->lembar_revisi);
            $validatedData['lembar_revisi'] = AppHelper::instance()->uploadLampiran($request->lembar_revisi, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->berita_acara) {
            AppHelper::instance()->deleteLampiran($jilid->berita_acara);
            $validatedData['berita_acara'] = AppHelper::instance()->uploadLampiran($request->berita_acara, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->artikel) {
            AppHelper::instance()->deleteLampiran($jilid->artikel);
            $validatedData['artikel'] = AppHelper::instance()->uploadLampiran($request->artikel, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        } elseif ($request->artikel_link) {
            AppHelper::instance()->deleteLampiran($jilid->artikel);
            $validatedData['artikel'] = $request->artikel_link;
        }
        if ($request->panduan) {
            AppHelper::instance()->deleteLampiran($jilid->panduan);
            $validatedData['panduan'] = AppHelper::instance()->uploadLampiran($request->panduan, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        } elseif ($request->panduan_link) {
            $validatedData['panduan'] = $request->panduan_link;
        }
        if ($request->lampiran) {
            AppHelper::instance()->deleteLampiran($jilid->lampiran);
            $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        }
        if ($request->bukti_nilai_instansi) {
            AppHelper::instance()->deleteLampiran($jilid->bukti_nilai_instansi);
            $validatedData['bukti_nilai_instansi'] = AppHelper::instance()->uploadLampiran($request->bukti_nilai_instansi, 'lampirans', $mahasiswa->nim, 'pengumpulan_akhir');
        }

        $validatedData['status'] = Jilid::JILID_REVIEW;
        $jilid->update($validatedData);

        return redirect()->route('kp.pengumpulan-akhir.mahasiswa')->with('success', 'Pengajuan pengumpulan akhir berhasil. Silahkan tunggu validasi dari Admin');
    }

    public function acc(Request $request, $id)
    {
        $jilid = Jilid::findOrFail($id);

        if ($request->catatan) {
            $revisi = new RevisiJilid;
            $revisi->catatan = $request->catatan;
            $revisi->jilid_id = $jilid->id;
            $jilid->revisis()->save($revisi);
        }

        $updateData = [
            'status' => $request->status,
        ];

        // Tambahkan total_pembayaran jika ada (dari fotokopian)
        if ($request->total_pembayaran) {
            $updateData['total_pembayaran'] = $request->total_pembayaran;
        }

        $jilid->update($updateData);

        if ($request->status == Jilid::JILID_SELESAI) {
            $message = 'Pengumpulan Akhir KP Anda Berstatus SELESAI. Selamat!';
            if ($request->total_pembayaran) {
                $message .= ' Silahkan ambil di FOTOKOPIAN FASTIKOM dan lakukan pembayaran sebesar Rp ' . number_format($request->total_pembayaran, 0, ',', '.');
            }
            AppHelper::instance()->send_mail([
                'mail' => $jilid->mahasiswa->email,
                'subject' => 'Pengumpulan Akhir KP',
                'title' => 'EKAPTA',
                'message' => $message,
            ]);
        } elseif ($request->status == Jilid::JILID_VALID) {
            AppHelper::instance()->send_mail([
                'mail' => $jilid->mahasiswa->email,
                'subject' => 'Pengumpulan Akhir KP',
                'title' => 'EKAPTA',
                'message' => 'Dokumen Kerja Praktek sudah dikonfirmasi oleh admin dan siap untuk dijilid. Silahkan konfirmasi dan melakukan pembayaran ke Fotocopy Fastikom dengan membawa dokumen-dokumen asli.',
            ]);
        } elseif ($request->status == Jilid::JILID_REVISI) {
            AppHelper::instance()->send_mail([
                'mail' => $jilid->mahasiswa->email,
                'subject' => 'Pengumpulan Akhir KP',
                'title' => 'EKAPTA',
                'message' => 'Dokumen Kerja Praktek Berstatus REVISI. Silahkan submit ulang! <br>Ket: '. $request->catatan,
            ]);
        }

        return redirect()->route('kp.pengumpulan-akhir.index')->with('success', 'Pengajuan pengumpulan akhir berhasil di update');
    }

    public function confirmCompleted($id)
    {
        $jilid = Jilid::findOrFail($id);

        if ($jilid->status != Jilid::JILID_SELESAI) {
            return back();
        }

        $jilid->update([
            'is_completed' => Jilid::JILID_COMPLETED,
        ]);

        return redirect()->route('kp.pengumpulan-akhir.index')->with('success', 'Konfirmasi setor ke perpus berhasil disimpan');
    }
}

