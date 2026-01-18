<?php

namespace App\Http\Controllers\KP;

use App\Models\KP\Pengajuan;
use App\Models\Prodi;
use App\Models\KP\RevisiPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AppHelper;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Validation\Rule;

class PengajuanController extends \App\Http\Controllers\Controller
{

    public function pengajuanProdi()
    {
        $prodiUser = Auth::guard('prodi')->user();
        
        // Ambil semua pengajuan yang:
        // 1. prodi_id match dengan id prodi yang login, ATAU
        // 2. mahasiswa.prodi match dengan kode prodi yang login (untuk data lama)
        $pengajuans_review = Pengajuan::where(function($query) use ($prodiUser) {
                $query->where('prodi_id', $prodiUser->id)
                    ->orWhereHas('mahasiswa', function($q) use ($prodiUser) {
                        $q->where('prodi', $prodiUser->kode)
                          ->orWhere('prodi', $prodiUser->namaprodi);
                    });
            })
            ->where('status', Pengajuan::REVIEW)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $pengajuans_acc = Pengajuan::where(function($query) use ($prodiUser) {
                $query->where('prodi_id', $prodiUser->id)
                    ->orWhereHas('mahasiswa', function($q) use ($prodiUser) {
                        $q->where('prodi', $prodiUser->kode)
                          ->orWhere('prodi', $prodiUser->namaprodi);
                    });
            })
            ->where('status', Pengajuan::DITERIMA)
            ->orderBy('tanggal_acc', 'desc')
            ->get();
            
        $pengajuans_revisi = Pengajuan::where(function($query) use ($prodiUser) {
                $query->where('prodi_id', $prodiUser->id)
                    ->orWhereHas('mahasiswa', function($q) use ($prodiUser) {
                        $q->where('prodi', $prodiUser->kode)
                          ->orWhere('prodi', $prodiUser->namaprodi);
                    });
            })
            ->where('status', Pengajuan::REVISI)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $pengajuans_ditolak = Pengajuan::where(function($query) use ($prodiUser) {
                $query->where('prodi_id', $prodiUser->id)
                    ->orWhereHas('mahasiswa', function($q) use ($prodiUser) {
                        $q->where('prodi', $prodiUser->kode)
                          ->orWhere('prodi', $prodiUser->namaprodi);
                    });
            })
            ->where('status', Pengajuan::DITOLAK)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kp.pages.prodi.pengajuan.pengajuan', [
            'title' => 'Pengajuan Kerja Praktek',
            'active' => 'pengajuan-kp',
            'pengajuans' => $pengajuans_review,
            'sidebar' => 'kp.partials.sidebarProdi',
            'module' => 'kp',
            'pengajuans_acc' => $pengajuans_acc,
            'pengajuans_revisi' => $pengajuans_revisi,
            'pengajuans_ditolak' => $pengajuans_ditolak,
        ]);
    }

    public function pengajuanMahasiswa()
    {
        if(Auth::guard('mahasiswa')->user()->email == '-'){
            return redirect()->route('kp.profile');
        }
        $pengajuans = Pengajuan::orderBy('created_at','desc')->where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->get();
        $pengajuans_acc = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', Pengajuan::DITERIMA)->get();
        return view('kp.pages.mahasiswa.pengajuan.pengajuan', [
            'title' => 'Pengajuan Kerja Praktek',
            'active' => 'pengajuan-kp',
            'pengajuans' => $pengajuans,
            'pengajuans_acc' => $pengajuans_acc,
        ]);
    }

    public function pengajuanAdmin()
    {
        $pengajuans = Pengajuan::orderBy('created_at', 'desc')->get();
        return view('kp.pages.admin.pengajuan.pengajuan', [
            'title' => 'Pengajuan Kerja Praktek',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            'active' => 'pengajuan-kp',
            'pengajuans' => $pengajuans,
        ]);
    }

    public function create()
    {
        $pengajuan = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', Pengajuan::DITERIMA)->first();
        if ($pengajuan) {
            return back()->with('warning', 'Pengajuan anda sudah diterima');
        }
        return view('kp.pages.mahasiswa.pengajuan.create', [
            'title' => 'Form Pengajuan Kerja Praktek',
            'active' => 'pengajuan-kp',
        ]);
    }

    public function pengajuanDetail($id)
    {
        $pengajuan = Pengajuan::with(['revisis','bimbingan_canceleds'])->findOrFail($id);
        if ($pengajuan->mahasiswa->nim != Auth::guard('mahasiswa')->user()->nim) {
            return back()->with('warning', 'Pengajuan tidak ditemukan');
        }
        return view('kp.pages.mahasiswa.pengajuan.detail', [
            'title' => 'Detail pengajuan',
            'active' => 'pengajuan-kp',
            'pengajuan' => $pengajuan,
            'revisis' => $pengajuan->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function pengajuanReview($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $prodi = Auth::guard('prodi')->user();
        $mahasiswa = $pengajuan->mahasiswa;
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        // Get dosens by prodi mahasiswa (via dosen_prodis pivot table)
        // Dosen yang double prodi (TI+MI) akan muncul di kedua prodi
        $prodiMahasiswa = Prodi::where('namaprodi', $mahasiswa->prodi)
            ->orWhere('kode', $mahasiswa->prodi)
            ->first();
        $dosens = $prodiMahasiswa ? $prodiMahasiswa->dosens : collect();
        $pengajuanCekIsPlagiat = Pengajuan::where('judul', 'LIKE', '%' . $pengajuan->judul . '%')->whereNotIn('id',[$pengajuan->id])->get();

        if ($pengajuan->prodi->namaprodi != $prodi->namaprodi){
            abort(404);
        }

        $data = [
            'title' => 'Review pengajuan',
            'active' => 'pengajuan-kp',
            'pengajuan' => $pengajuan,
            'dosens' => $dosens,
            'sidebar' => 'kp.partials.sidebarProdi',
            'module' => 'kp',
            'revisis' => $pengajuan->revisis()->orderBy('created_at', 'desc')->paginate(5),
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'mahasiswa' => $mahasiswa,
            'pengajuanCekIsPlagiat' => $pengajuanCekIsPlagiat,
        ];

        return view('kp.pages.prodi.pengajuan.review', $data);
    }

    public function pengajuanReviewAdmin($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        return view('kp.pages.admin.pengajuan.review', [
            'title' => 'Review pengajuan',
            'active' => 'pengajuan-kp',
            'pengajuan' => $pengajuan,
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            'revisis' => $pengajuan->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function store(Request $request)
    {
        $cekPengajuan = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->whereIn('status', [Pengajuan::REVIEW, Pengajuan::REVISI, Pengajuan::DITERIMA])->get();
        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', Auth::guard('mahasiswa')->user()->prodi)
            ->orWhere('namaprodi', Auth::guard('mahasiswa')->user()->prodi)
            ->first();

        if ($cekPengajuan->isEmpty()) {
            $validatedData = $request->validate([
                'judul' => ['required', 'min:5'],
                'lokasi_kp' => ['required'],
                'alamat_instansi' => ['required'],
                'deskripsi' => ['nullable'],
                'lampiran' => 'required|mimes:pdf,doc,docx|file|max:10000',
                'files_pendukung' => ['required', 'mimes:pdf,zip,rar', 'max:10000'],
            ]);

            $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);

            $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->file('lampiran'), 'lampirans', $mahasiswa->nim, 'pengajuan');
            if ($request->hasFile('files_pendukung')) {
                $validatedData['files_pendukung'] = AppHelper::instance()->uploadLampiran($request->file('files_pendukung'), 'lampirans', $mahasiswa->nim, 'pengajuan');
            }

            $validatedData['mahasiswa_id'] = Auth::guard('mahasiswa')->user()->id;

            // Pastikan prodi ditemukan
            if (!$prodi) {
                return redirect()->route('kp.pengajuan.mahasiswa')->with('error', 'Prodi tidak ditemukan. Hubungi admin.');
            }

            $validatedData['prodi_id'] = $prodi->id;

            Pengajuan::create($validatedData);

            return redirect()->route('kp.pengajuan.mahasiswa')->with('success', 'Berhasil melakukan pengajuan kerja Praktek');
        } else {
            return redirect()->route('kp.pengajuan.mahasiswa')->with('warning', 'Menunggu review dari prodi');
        }
    }

    public function edit($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        if ($pengajuan->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            abort(404);
        }

        if ($pengajuan->status == Pengajuan::REVIEW || $pengajuan->status == Pengajuan::DITERIMA) {
            return back()->with('warning', 'Pengajuan tidak bisa diedit');
        }

        return view('kp.pages.mahasiswa.pengajuan.edit', [
            'title' => 'Form Edit Pengajuan Kerja Praktek',
            'active' => 'pengajuan-kp',
            'pengajuan' => $pengajuan,
        ]);
    }

    public function update(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        $validatedData = $request->validate([
            'judul' => ['required', 'min:5'],
            'lokasi_kp' => ['required'],
            'alamat_instansi' => ['required'],
            'deskripsi' => ['nullable'],
            'lampiran' => [Rule::requiredIf(function () use($request) {
                if (empty($request->lampiran)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf', 'max:5000'],
            'files_pendukung' => ['nullable', 'mimes:pdf,zip,rar', 'max:10000']
        ]);

        $mahasiswa = $pengajuan->mahasiswa;
        if ($request->file('lampiran')) {
            AppHelper::instance()->deleteLampiran($pengajuan->lampiran);
            $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans', $mahasiswa->nim, 'pengajuan');
        }

        if ($request->file('files_pendukung')) {
            if ($pengajuan->files_pendukung) {
                AppHelper::instance()->deleteLampiran($pengajuan->files_pendukung);
            }
            $validatedData['files_pendukung'] = AppHelper::instance()->uploadLampiran($request->files_pendukung, 'lampirans', $mahasiswa->nim, 'pengajuan');
        }

        $validatedData['nim'] = Auth::guard('mahasiswa')->user()->nim;
        $validatedData['status'] = Pengajuan::REVIEW;

        $pengajuan->update($validatedData);
        return redirect()->route('kp.pengajuan.mahasiswa')->with('success', 'Berhasil melakukan pengajuan kerja Praktek');

    }

    public function delete(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($pengajuan->lampiran);

        foreach ($pengajuan->revisis as $revisi) {
            AppHelper::instance()->deleteLampiran($revisi->lampiran);
        }

        $pengajuan->revisis->each->delete();
        $pengajuan->delete();
        return back()->with('success', 'Pengajuan berhasil dihapus');
    }

    public function accPengajuan(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($pengajuan->status == Pengajuan::DITOLAK) {
            return back()->with('error', 'Pengajuan tidak bisa diedit');
        } else {
            if ($pengajuan->status == Pengajuan::REVIEW) {
                // Save revisi/catatan if provided
                if ($request->catatan) {
                    $revisi = new RevisiPengajuan;
                    $revisi->catatan = $request->catatan;
                    $pengajuan->revisis()->save($revisi);
                }

                // Update pengajuan status (tanpa pilih dosen - dosen dipilih via Ploting)
                $pengajuan->update([
                    'status' => Pengajuan::DITERIMA,
                    'tanggal_acc' => now(),
                ]);

                // Send email notification (wrapped in try-catch to prevent failure)
                try {
                    if ($pengajuan->mahasiswa->email && $pengajuan->mahasiswa->email != '-') {
                        AppHelper::instance()->send_mail([
                            'mail' => $pengajuan->mahasiswa->email,
                            'subject' => 'Pengajuan Kerja Praktek',
                            'title' => 'EKAPTA',
                            'message' => 'Selamat Pengajuan Kerja Praktek Anda Berstatus DITERIMA. Silahkan tunggu prodi menentukan dosen pembimbing Anda.',
                        ]);
                    }
                } catch (\Exception $e) {
                    // Email failed but don't block the process
                }

                return back()->with('success', 'Pengajuan berhasil disetujui. Silahkan lakukan Ploting Dosen Pembimbing.');
            }
        }
    }

    public function cancelAcc(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($pengajuan->status != Pengajuan::DITERIMA) {
            return back()->with('warning', 'Pengajuan tidak ditemukan');
        }
        $pengajuan->update([
            'status' => Pengajuan::REVIEW,
            'tanggal_acc' => null,
        ]);
        return back()->with('success', 'Acc pengajuan berhasil dibatalkan');
    }

    public function tolakPengajuan(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($pengajuan->status == Pengajuan::DITERIMA || $pengajuan->status == Pengajuan::DITOLAK) {
            return back()->with('warning', 'pengajuan sudah tidak bisa ditolak');
        } else {
            $revisi = new RevisiPengajuan;
            if ($request->catatan) {
                $revisi->catatan = $request->catatan;

                $request->validate([
                    'lampiran' => [Rule::requiredIf(function () use($request) {
                        if (empty($request->lampiran)) {
                            return false;
                        }
                        return true;
                    }), 'mimes:pdf', 'max:5000']
                ]);

                if ($request->file('lampiran')) {
                    $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans', $pengajuan->mahasiswa->nim, 'pengajuan');
                }

                $pengajuan->revisis()->save($revisi);
            }

            // Increment jumlah_tolak
            $jumlahTolak = ($pengajuan->jumlah_tolak ?? 0) + 1;

            $pengajuan->update([
                'status' => Pengajuan::DITOLAK,
                'jumlah_tolak' => $jumlahTolak,
            ]);

            // Kirim email dengan info sisa kesempatan
            $sisaKesempatan = Pengajuan::MAX_TOLAK - $jumlahTolak;
            $pesanTambahan = $sisaKesempatan > 0 
                ? "Anda masih bisa mengajukan {$sisaKesempatan}x lagi." 
                : "Anda sudah mencapai batas maksimal pengajuan.";

            if ($pengajuan->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $pengajuan->mahasiswa->email,
                    'subject' => 'Pengajuan Kerja Praktek',
                    'title' => 'EKAPTA',
                    'message' => 'Pengajuan Kerja Praktek Anda Berstatus DITOLAK. ' . $pesanTambahan . '<br><br> Catatan : '.$request->catatan,
                ]);
            }
            return redirect()->route('kp.pengajuan.prodi')->with('success', 'Pengajuan berhasil ditolak');
        }
    }

    public function cancelTolak(Request $request)
    {
        $cekPengajuan = Pengajuan::where('mahasiswa_id', $request->mahasiswa_id)->whereIn('status', [Pengajuan::DITERIMA, Pengajuan::REVIEW, Pengajuan::REVISI])->first();
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($cekPengajuan) {
            return back()->with('warning', 'Pengajuan tidak bisa dibatalkan, karena sudah ada pengajuan dengan status Diterima/Direview/Direvisi');
        } else {
            if ($pengajuan->status != Pengajuan::DITOLAK) {
                return back()->with('warning', 'Pengajuan tidak ditemukan');
            }
            $pengajuan->update([
                'status' => Pengajuan::REVIEW,
            ]);
            return back()->with('success', 'Tolak pengajuan berhasil dibatalkan');
        }
    }

    public function revisiPengajuan(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($pengajuan->status == Pengajuan::DITERIMA || $pengajuan->status == Pengajuan::DITOLAK) {
            return back()->with('warning', 'Pengajuan sudah tidak bisa direvisi');
        } else {
            if ($request->catatan) {
                $revisi = new RevisiPengajuan;
                $revisi->catatan = $request->catatan;

                $request->validate([
                    'lampiran' => [Rule::requiredIf(function () use($request) {
                        if (empty($request->lampiran)) {
                            return false;
                        }
                        return true;
                    }), 'mimes:pdf,docx', 'max:5000']
                ]);

                if ($request->file('lampiran')) {
                    $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans', $pengajuan->mahasiswa->nim, 'pengajuan');
                }

                $pengajuan->update([
                    'status' => Pengajuan::REVISI,
                ]);
                if ($pengajuan->mahasiswa->email != '-') {
                    AppHelper::instance()->send_mail([
                        'mail' => $pengajuan->mahasiswa->email,
                        'subject' => 'Pengajuan Kerja Praktek',
                        'title' => 'EKAPTA',
                        'message' => 'Pengajuan Kerja Praktek Anda Berstatus REVISI. Silahkan perbaiki kemudian submit ulang. <br><br> Catatan Revisi: '.$request->catatan,
                    ]);
                }
                $pengajuan->revisis()->save($revisi);
            }

            return redirect()->route('kp.pengajuan.prodi')->with('success', 'Pengajuan berhasil direvisi');
        }
    }

    public function deleteRevisiPengajuan(Request $request)
    {
        $revisi = RevisiPengajuan::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');;
    }

    public function editJudulPengajuan(Request $request, $id){
        $pengajuan = Pengajuan::findOrFail($id);

        $pengajuan->update([
            'judul' => $request->judul,
        ]);

        return back()->with('success','Judul KP berhasil di update.');
    }
}

