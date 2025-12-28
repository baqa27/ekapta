<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\RevisiPendaftaran;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pengajuan;
use App\Models\Prodi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    public function pendaftaranAdmin()
    {
        $pendaftarans = Pendaftaran::where('status', Pendaftaran::REVIEW)->orderBy('created_at', 'desc')->get();
        $pendaftarans_acc = Pendaftaran::where('status', Pendaftaran::DITERIMA)->orderBy('created_at', 'desc')->get();
        $pendaftarans_revisi = Pendaftaran::where('status', Pendaftaran::REVISI)->orderBy('created_at', 'desc')->get();
        return view('pages.admin.pendaftaran.pendaftaran', [
            'title' => 'Pendaftaran Kerja Praktek',
            'active' => 'pendaftaran',
            'sidebar' => 'partials.sidebarAdmin',
            'pendaftarans' => $pendaftarans,
            'pendaftarans_acc' => $pendaftarans_acc,
            'pendaftarans_revisi' => $pendaftarans_revisi,
        ]);
    }

    public function pendaftaranMahasiswa()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        if($mahasiswa->email == '-'){
            return redirect()->route('profile');
        }
        
        // Cek tahapan: Pengajuan harus ACC dulu
        if (!AppHelper::canAccessPendaftaran($mahasiswa)) {
            return redirect()->route('pengajuan.mahasiswa')->with('warning', 'Selesaikan tahap Pengajuan KP terlebih dahulu. Pengajuan harus sudah di-ACC oleh Prodi.');
        }
        
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();

        // KP workflow: single dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback for legacy data
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', Dosen::UTAMA)->first();
        }

        $pendaftarans = Pendaftaran::orderBy('created_at','desc')->where('mahasiswa_id', $mahasiswa->id)->with(['revisis'])->get();

        $pendaftaranIsAcc = Pendaftaran::where('pengajuan_id', $pengajuan->id)->with(['revisis'])->where('status', Pendaftaran::DITERIMA)->get();
        $pendaftarans_review_acc_revisi = Pendaftaran::where('pengajuan_id', $pengajuan->id)->with(['revisis'])->whereIn('status', [Pendaftaran::REVIEW, Pendaftaran::DITERIMA, Pendaftaran::REVISI])->get();

        return view('pages.mahasiswa.pendaftaran.pendaftaran', [
            'title' => 'Pendaftaran Kerja Praktek',
            'active' => 'pendaftaran',
            'pendaftarans' => $pendaftarans,
            'dosen_pembimbing' => $dosenPembimbing,
            'pendaftaranIsAcc' => $pendaftaranIsAcc,
            'pendaftarans_review_acc_revisi' => $pendaftarans_review_acc_revisi,
        ]);
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();

        $pendaftarans_review_acc = Pendaftaran::where('pengajuan_id', $pengajuan->id)->whereIn('status', [Pendaftaran::DITERIMA, Pendaftaran::REVIEW])->get();

        if (count($pendaftarans_review_acc) != 0) {
            return redirect('pendaftaran-mahasiswa')->with('warning', 'Anda sudah melakukan pendaftaran kerja Praktek');
        } else if (count(Auth::guard('mahasiswa')->user()->dosens) == 0) {
            return back()->with('warning', 'Silahkan tunggu ploting dosen pembimbing oleh Prodi');
        }

        // KP workflow: single dosen pembimbing (status = 'pembimbing')
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback: check 'utama' for legacy data
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        return view('pages.mahasiswa.pendaftaran.create', [
            'title' => 'Form Pendaftaran Kerja Praktek',
            'active' => 'pendaftaran',
            'dosen_pembimbing' => $dosenPembimbing,
            'pengajuan' => $pengajuan,
        ]);
    }

    public function store(Request $request)
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();

        $pendaftarans_review_acc = Pendaftaran::where('pengajuan_id', $pengajuan->id)->whereIn('status', [Pendaftaran::DITERIMA, Pendaftaran::REVIEW])->get();

        if (count($pendaftarans_review_acc) != 0) {
            return redirect('pendaftaran-mahasiswa')->with('warning', 'Anda sudah melakukan pendaftaran');
        } else {
            $validatedData = $request->validate([
                'nomor_pembayaran' => 'required',
                'tanggal_pembayaran' => 'required',
                'biaya' => 'required',
                'lampiran_1' => ['required', 'mimes:pdf', 'max:5000'],
                'lampiran_2' => ['required', 'mimes:pdf', 'max:5000'],
                'lampiran_3' => ['required', 'mimes:pdf', 'max:5000'],
                'lampiran_4' => ['required', 'mimes:pdf,png,jpg,jpeg', 'max:5000'],
                'lampiran_5' => ['required', 'mimes:pdf,png,jpg,jpeg', 'max:5000'],
                'lampiran_6' => ['required', 'mimes:pdf', 'max:5000'],
                'lampiran_7' => ['required', 'mimes:pdf', 'max:5000'],
                'dokumen_pendukung_kp' => ['required', 'mimes:pdf', 'max:5000'],
            ]);

            $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_1'), 'lampirans', $mahasiswa->nim, 'pendaftaran');
            $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_2'), 'lampirans', $mahasiswa->nim, 'pendaftaran');
            $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_3'), 'lampirans', $mahasiswa->nim, 'pendaftaran');
            $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_4'), 'lampirans', $mahasiswa->nim, 'pendaftaran');
            $validatedData['lampiran_5'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_5'), 'lampirans', $mahasiswa->nim, 'pendaftaran');
            $validatedData['lampiran_6'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_6'), 'lampirans', $mahasiswa->nim, 'pendaftaran');
            $validatedData['lampiran_7'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_7'), 'lampirans', $mahasiswa->nim, 'pendaftaran');
            $validatedData['dokumen_pendukung_kp'] = AppHelper::instance()->uploadLampiran($request->file('dokumen_pendukung_kp'), 'lampirans', $mahasiswa->nim, 'pendaftaran');

            $validatedData['mahasiswa_id'] = $mahasiswa->id;
            $validatedData['pengajuan_id'] = $pengajuan->id;

            setlocale(LC_TIME, 'id');
            $tanggal_pembayaran = Carbon::parse($request->tanggal_pembayaran);
            $validatedData['tanggal_pembayaran'] = $tanggal_pembayaran->day.' '.$tanggal_pembayaran->monthName.' '.$tanggal_pembayaran->year;

            Pendaftaran::create($validatedData);
            return redirect('pendaftaran-mahasiswa')->with('success', 'Berhasil melakukan pendaftaran');
        }
    }

    public function edit($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        if ($pendaftaran->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            abort(404);
        }

        if ($pendaftaran->status == Pendaftaran::REVIEW || $pendaftaran->status == Pendaftaran::DITERIMA) {
            return redirect('pendaftaran-mahasiswa');
        }

        if ($pendaftaran->status == Pendaftaran::REVIEW ||  $pendaftaran->status == Pendaftaran::DITERIMA) {
            return back()->with('warning', 'Pendaftaran tidak bisa diedit');
        }

        $mahasiswa = Mahasiswa::where('id', $pendaftaran->mahasiswa_id)->first();
        // KP workflow: single dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', Dosen::UTAMA)->first();
        }

        return view('pages.mahasiswa.pendaftaran.edit', [
            'title' => 'Form Edit Pendaftaran Kerja Praktek',
            'active' => 'pendaftaran',
            'dosen_pembimbing' => $dosenPembimbing,
            'pendaftaran' => $pendaftaran,
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function pendaftaranReview($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $mahasiswa = Mahasiswa::where('id', $pendaftaran->mahasiswa_id)->first();
        // KP workflow: single dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', Dosen::UTAMA)->first();
        }

        return view('pages.admin.pendaftaran.review', [
            'title' => 'Review Pendaftaran Kerja Praktek',
            'active' => 'pendaftaran',
            'sidebar' => 'partials.sidebarAdmin',
            'dosen_pembimbing' => $dosenPembimbing,
            'pendaftaran' => $pendaftaran,
            'mahasiswa' => $mahasiswa,
            'revisis' => $pendaftaran->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function pendaftaranDetail($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $mahasiswa = $pendaftaran->mahasiswa;

        // KP workflow: single dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', Dosen::UTAMA)->first();
        }

        if ($pendaftaran->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            abort(404);
        }

        if (!$pendaftaran) {
            return back()->with('warning', 'Pendaftaran tidak ditemukan');
        }

        return view('pages.mahasiswa.pendaftaran.detail', [
            'title' => 'Detail Pendaftaran Kerja Praktek',
            'active' => 'pendaftaran',
            'dosen_pembimbing' => $dosenPembimbing,
            'pendaftaran' => $pendaftaran,
            'revisis' => $pendaftaran->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function update(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        $validatedData = $request->validate([
            'nomor_pembayaran' => 'required',
            'biaya' => 'required',
            'lampiran_1' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_1)) {return false;}
                return true;
            }), 'mimes:pdf', 'max:5000'],
            'lampiran_2' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_2)) {return false;}
                return true;
            }), 'mimes:pdf', 'max:5000'],
            'lampiran_3' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_3)) {return false;}
                return true;
            }), 'mimes:pdf', 'max:5000'],
            'lampiran_4' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_4)) {return false;}
                return true;
            }), 'mimes:pdf,png,jpg,jpeg', 'max:5000'],
            'lampiran_5' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_5)) {return false;}
                return true;
            }), 'mimes:pdf,png,jpg,jpeg', 'max:5000'],
            'lampiran_6' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_6)) {return false;}
                return true;
            }), 'mimes:pdf', 'max:5000'],
            'lampiran_7' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran_7)) {return false;}
                return true;
            }), 'mimes:pdf', 'max:5000'],
            'dokumen_pendukung_kp' => [Rule::requiredIf(function () {
                if (empty($this->request->dokumen_pendukung_kp)) {return false;}
                return true;
            }), 'mimes:pdf', 'max:5000'],
        ]);

        $mahasiswa = $pendaftaran->mahasiswa;
        if ($request->file('lampiran_1')) {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_1);
            $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->lampiran_1, 'lampirans', $mahasiswa->nim, 'pendaftaran');
        }
        if ($request->file('lampiran_2')) {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_2);
            $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->lampiran_2, 'lampirans', $mahasiswa->nim, 'pendaftaran');
        }
        if ($request->file('lampiran_3')) {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_3);
            $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->lampiran_3, 'lampirans', $mahasiswa->nim, 'pendaftaran');
        }
        if ($request->file('lampiran_4')) {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_4);
            $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->lampiran_4, 'lampirans', $mahasiswa->nim, 'pendaftaran');
        }
        if ($request->file('lampiran_5')) {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_5);
            $validatedData['lampiran_5'] = AppHelper::instance()->uploadLampiran($request->lampiran_5, 'lampirans', $mahasiswa->nim, 'pendaftaran');
        }
        if ($request->file('lampiran_6')) {
            if($pendaftaran->lampiran_6) AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_6);
            $validatedData['lampiran_6'] = AppHelper::instance()->uploadLampiran($request->lampiran_6, 'lampirans', $mahasiswa->nim, 'pendaftaran');
        }
        if ($request->file('lampiran_7')) {
             if($pendaftaran->lampiran_7) AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_7);
            $validatedData['lampiran_7'] = AppHelper::instance()->uploadLampiran($request->lampiran_7, 'lampirans', $mahasiswa->nim, 'pendaftaran');
        }
        if ($request->file('dokumen_pendukung_kp')) {
             if($pendaftaran->dokumen_pendukung_kp) AppHelper::instance()->deleteLampiran($pendaftaran->dokumen_pendukung_kp);
            $validatedData['dokumen_pendukung_kp'] = AppHelper::instance()->uploadLampiran($request->dokumen_pendukung_kp, 'lampirans', $mahasiswa->nim, 'pendaftaran');
        }

        $validatedData['mahasiswa_id'] = $mahasiswa->id;
        $validatedData['judul'] = $pendaftaran->judul;
        $validatedData['status'] = Pendaftaran::REVIEW;

        if ($request->tanggal_pembayaran) {
            setlocale(LC_TIME, 'id');
            $tanggal_pembayaran = Carbon::parse($request->tanggal_pembayaran);
            $validatedData['tanggal_pembayaran'] = $tanggal_pembayaran->day.' '.$tanggal_pembayaran->monthName.' '.$tanggal_pembayaran->year;;
        }

        $pendaftaran->update($validatedData);
        return redirect('pendaftaran-mahasiswa')->with('success', 'Pendaftaran berhasil diupdate');
    }

    public function delete(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        if ($pendaftaran->status == Pendaftaran::DITERIMA) {
            return back()->with('error', 'Pendaftaran gagal dihapus');
        } else {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_2);
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_3);
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_4);
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_5);
            $pendaftaran->delete();
            return back()->with('success', 'Pendaftaran berhasil dihapus');
        }
    }

    public function accPendaftaran(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);

        $mahasiswa = Mahasiswa::where('id', $pendaftaran->mahasiswa_id)->first();
        $mahasiswa->update([
            'thmasuk' => $request->tahun_masuk,
        ]);

        // KP hanya butuh 1 dosen pembimbing (status = 'pembimbing')
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();

        $pendaftaran_disabled = Pendaftaran::where('mahasiswa_id', $mahasiswa->id)->where('status', Pendaftaran::DISABLED)->first();

        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();

        // Validasi dosen pembimbing sudah di-assign di tahap pengajuan
        if (!$dosenPembimbing) {
            return back()->with('warning', 'Dosen pembimbing belum ditentukan. Pastikan Prodi sudah menentukan dosen pembimbing di tahap Pengajuan KP.');
        } elseif (count($prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->get()) == 0) {
            return back()->with('warning', 'Bagian bimbingan untuk prodi ' . $mahasiswa->prodi . ' dan tahun masuk '.$mahasiswa->thmasuk.' masih kosong');
        } elseif ($pendaftaran->status == Pendaftaran::DITERIMA) {
            return back()->with('warning', 'Pendaftaran sudah diacc');
        } else {
            $pendaftaran->update([
                'status' => Pendaftaran::DITERIMA,
                'tanggal_acc' => now(),
            ]);

            if (!$pendaftaran_disabled) {
                // KP: Otomatis create bimbingan dengan 1 dosen pembimbing untuk setiap bagian
                foreach ($prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->get() as $bagian) {
                    $bimbingan = Bimbingan::create([
                        'mahasiswa_id' => $mahasiswa->id,
                        'bagian_id' => $bagian->id,
                        'pembimbing' => 'pembimbing', // KP hanya 1 pembimbing
                    ]);
                    $bimbingan->dosens()->attach([$dosenPembimbing->id]);
                }
            }
            if ($pendaftaran->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $pendaftaran->mahasiswa->email,
                    'subject' => 'Pendaftaran Kerja Praktek',
                    'title' => 'EKAPTA',
                    'message' => 'Selamat Pendaftaran Kerja Praktek Anda Berstatus DITERIMA. Anda bisa memulai Bimbingan Kerja Praktek.',
                ]);
            }
            return back()->with('success', 'Pendaftaran berhasil diacc');
        }
    }

    public function cancelAcc(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        $mahasiswa = Mahasiswa::where('id', $pendaftaran->mahasiswa_id)->first();
        if ($pendaftaran->status != Pendaftaran::DITERIMA) {
            return back()->with('error', 'Pendaftaran tidak ditemukan');
        } else {
            $pendaftaran->update([
                'status' => Pendaftaran::REVIEW,
                'tanggal_acc' => null,
            ]);
            $mahasiswa->bimbingans->each->delete();
            return redirect('pendaftaran/review/' . $pendaftaran->id)->with('success', 'Acc pendaftaran berhasil dibatalkan');
        }
    }

    public function revisiPendaftaran(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        $revisi = new RevisiPendaftaran;
        $revisi->catatan = $request->catatan;
        $request->validate([
            'lampiran' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf,docx', 'max:5000']
        ]);
        if ($request->file('lampiran')) {
            $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans', $pendaftaran->mahasiswa->nim, 'pendaftaran');
        }
        if ($pendaftaran->status == Pendaftaran::REVIEW) {
            $pendaftaran->update([
                'status' => Pendaftaran::REVISI,
            ]);
            $pendaftaran->revisis()->save($revisi);
            if ($pendaftaran->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $pendaftaran->mahasiswa->email,
                    'subject' => 'Pendaftaran Kerja Praktek',
                    'title' => 'EKAPTA',
                    'message' => 'Pendaftaran Kerja Praktek Anda Berstatus REVISI. Silahkan perbaiki kemudian submit ulang. <br><br> Catatan Revisi: '.$request->catatan,
                ]);
            }
            return redirect('pendaftaran-admin')->with('success', 'Pendaftaran berhasil direvisi');
        } elseif ($pendaftaran->status == Pendaftaran::REVISI) {
            $pendaftaran->revisis()->save($revisi);
            return back()->with('success', 'Revisi berhasil ditambahkan');
        }
    }

    public function deleteRevisiPendaftaran(Request $request)
    {
        $revisi = RevisiPendaftaran::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function disablePendaftaran($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => Pendaftaran::DISABLED,
        ]);

        return redirect('pendaftaran/create');
    }
}
