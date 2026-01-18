<?php

namespace App\Http\Controllers\TA;

use App\Models\TA\Pengajuan;
use App\Models\Prodi;
use App\Models\TA\RevisiPengajuan;
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
        //$pengajuans = Pengajuan::where('status', Pengajuan::REVIEW)->where('prodi', Auth::guard('prodi')->user()->namaprodi)->orderBy('created_at', 'desc')->get();
        //$pengajuans_acc = Pengajuan::where('status', Pengajuan::DITERIMA)->where('prodi', Auth::guard('prodi')->user()->namaprodi)->orderBy('created_at', 'desc')->get();
        //$pengajuans_revisi = Pengajuan::where('status', Pengajuan::REVISI)->where('prodi', Auth::guard('prodi')->user()->namaprodi)->orderBy('created_at', 'desc')->get();
        //$pengajuans_ditolak = Pengajuan::where('status', Pengajuan::DITOLAK)->where('prodi', Auth::guard('prodi')->user()->namaprodi)->orderBy('created_at', 'desc')->get();

        $prodi = Prodi::with(['pengajuansTA'])->findOrFail(Auth::guard('prodi')->user()->id);

        $pengajuans_review = $prodi->pengajuansTA()->where('status', Pengajuan::REVIEW)->orderBy('created_at', 'desc')->get();
        $pengajuans_acc = $prodi->pengajuansTA()->where('status', Pengajuan::DITERIMA)->orderBy('tanggal_acc', 'desc')->get();
        $pengajuans_revisi = $prodi->pengajuansTA()->where('status', Pengajuan::REVISI)->orderBy('created_at', 'desc')->get();
        $pengajuans_ditolak = $prodi->pengajuansTA()->where('status', Pengajuan::DITOLAK)->orderBy('created_at', 'desc')->get();

        return view('ta.pages.prodi.pengajuan.pengajuan', [
            'title' => 'Pengajuan Tugas Akhir',
            'active' => 'pengajuan-ta',
            'module' => 'ta',
            'pengajuans' => $pengajuans_review,
            'sidebar' => 'ta.partials.sidebarProdi',
            'module' => 'ta',
            'active' => 'pengajuan-ta',
            'module' => 'ta',
            'pengajuans_acc' => $pengajuans_acc,
            'pengajuans_revisi' => $pengajuans_revisi,
            'pengajuans_ditolak' => $pengajuans_ditolak,
        ]);
    }

    public function pengajuanMahasiswa()
    {
        if(Auth::guard('mahasiswa')->user()->email == '-'){
            return redirect()->route('profile');
        }
        $pengajuans = Pengajuan::orderBy('created_at','desc')->where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->get();
        $pengajuans_acc = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', Pengajuan::DITERIMA)->get();
        return view('ta.pages.mahasiswa.pengajuan.pengajuan', [
            'title' => 'Pengajuan Tugas Akhir',
            'active' => 'pengajuan-ta',
            'module' => 'ta',
            'pengajuans' => $pengajuans,
            'pengajuans_acc' => $pengajuans_acc,
        ]);
    }

    public function pengajuanAdmin()
    {
        $pengajuans = Pengajuan::orderBy('created_at', 'desc')->get();
        return view('ta.pages.admin.pengajuan.pengajuan', [
            'title' => 'Pengajuan Tugas Akhir',
            'sidebar' => 'ta.partials.sidebarAdmin',
            'module' => 'ta',
            'active' => 'pengajuan-ta',
            'module' => 'ta',
            'pengajuans' => $pengajuans,
        ]);
    }

    public function create()
    {
        $pengajuan = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', Pengajuan::DITERIMA)->first();
        if ($pengajuan) {
            return back()->with('warning', 'Pengajuan anda sudah diterima');
        }
        return view('ta.pages.mahasiswa.pengajuan.create', [
            'title' => 'Form Pengajuan Tugas Akhir',
            'active' => 'pengajuan-ta',
        'module' => 'ta',
        ]);
    }

    public function pengajuanDetail($id)
    {
        $pengajuan = Pengajuan::with(['revisis','bimbingan_canceleds'])->findOrFail($id);
        if ($pengajuan->mahasiswa->nim != Auth::guard('mahasiswa')->user()->nim) {
            return back()->with('warning', 'Pengajuan tidak ditemukan');
        }
        return view('ta.pages.mahasiswa.pengajuan.detail', [
            'title' => 'Detail pengajuan',
            'active' => 'pengajuan-ta',
            'module' => 'ta',
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
        //$dosens = Dosen::where('kodeprodi', Auth::guard('prodi')->user()->kode)->get();
        $dosens = $prodi->dosens;
        $pengajuanCekIsPlagiat = Pengajuan::where('judul', 'LIKE', '%' . $pengajuan->judul . '%')->whereNotIn('id',[$pengajuan->id])->get();

        if ($pengajuan->prodi->namaprodi != $prodi->namaprodi){
            abort(404);
        }
        // return $mahasiswa->bimbingans()->whereNotIn('status', ['dibatalkan'])->get();
        $data = [
            'title' => 'Review pengajuan',
            'active' => 'pengajuan-ta',
            'module' => 'ta',
            'pengajuan' => $pengajuan,
            'dosens' => $dosens,
            'sidebar' => 'ta.partials.sidebarProdi',
            'module' => 'ta',
            'revisis' => $pengajuan->revisis()->orderBy('created_at', 'desc')->paginate(5),
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'mahasiswa' => $mahasiswa,
            'pengajuanCekIsPlagiat' => $pengajuanCekIsPlagiat,
        ];

        return view('ta.pages.prodi.pengajuan.review', $data);
    }

    public function pengajuanReviewAdmin($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        return view('ta.pages.admin.pengajuan.review', [
            'title' => 'Review pengajuan',
            'active' => 'pengajuan-ta',
            'module' => 'ta',
            'pengajuan' => $pengajuan,
            'sidebar' => 'ta.partials.sidebarAdmin',
            'module' => 'ta',
            'revisis' => $pengajuan->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function store(Request $request)
    {
        $cekPengajuan = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->whereIn('status', [Pengajuan::REVIEW, Pengajuan::REVISI, Pengajuan::DITERIMA])->get();
        $prodi = Prodi::where('namaprodi', Auth::guard('mahasiswa')->user()->prodi)->first();

        if ($cekPengajuan->isEmpty()) {
            $validatedData = $request->validate([
                'judul' => ['required', 'min:5'],
                'deskripsi' => ['required', 'min:100'],
                'lampiran' => ['required', 'mimes:pdf', 'max:5000'],
            ]);
            if ($request->file('lampiran')) {
                $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
            }

            $validatedData['mahasiswa_id'] = Auth::guard('mahasiswa')->user()->id;

            $validatedData['prodi_id'] = $prodi->id;

            Pengajuan::create($validatedData);

            return redirect()->route('ta.pengajuan.mahasiswa')->with('success', 'Berhasil melakukan pengajuan tugas akhir');
        } else {
            return redirect()->route('ta.pengajuan.mahasiswa')->with('warning', 'Menunggu review dari prodi');
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

        return view('ta.pages.mahasiswa.pengajuan.edit', [
            'title' => 'Form Edit Pengajuan Tugas Akhir',
            'active' => 'pengajuan-ta',
            'module' => 'ta',
            'pengajuan' => $pengajuan,
        ]);
    }

    public function update(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        $validatedData = $request->validate([
            'judul' => ['required', 'min:5'],
            'deskripsi' => ['required', 'min:100'],
            'lampiran' => [Rule::requiredIf(function () use($request) {
                if (empty($request->lampiran)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf', 'max:5000']
        ]);

        if ($request->file('lampiran')) {
            AppHelper::instance()->deleteLampiran($pengajuan->lampiran);
            $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
        }

        $validatedData['nim'] = Auth::guard('mahasiswa')->user()->nim;
        $validatedData['status'] = Pengajuan::REVIEW;

        $pengajuan->update($validatedData);
        return redirect()->route('ta.pengajuan.mahasiswa')->with('success', 'Berhasil melakukan pengajuan tugas akhir');
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
                $revisi = new RevisiPengajuan;
                $revisi->catatan = $request->catatan;

                $pengajuan->update([
                    'status' => Pengajuan::DITERIMA,
                    'tanggal_acc' => now(),
                ]);
                if ($pengajuan->mahasiswa->email != '-') {
                    AppHelper::instance()->send_mail([
                        'mail' => $pengajuan->mahasiswa->email,
                        'subject' => 'Pengajuan Tugas Ahir',
                        'title' => 'EKAPTA',
                        'message' => 'Selamat Pengajuan Tugas Akhir Anda Berstatus DITERIMA. Silahkan tunggu ploting dosen pembimbing dan segera lakukan pendaftaran tugas akhir.',
                    ]);
                }

                $pengajuan->revisis()->save($revisi);

                return back()->with('success', 'Pengajuan berhasil diacc');
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
                    $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
                }

                $pengajuan->revisis()->save($revisi);
            }

            $pengajuan->update([
                'status' => Pengajuan::DITOLAK,
            ]);
            if ($pengajuan->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $pengajuan->mahasiswa->email,
                    'subject' => 'Pengajuan Tugas Ahir',
                    'title' => 'EKAPTA',
                    'message' => 'Pengajuan Tugas Akhir Anda Berstatus DITOLAK. Silahkan lakukan pengajuan ulang tugas akhir. <br><br> Catatan : '.$request->catatan,
                ]);
            }
            return redirect()->route('ta.pengajuan.prodi')->with('success', 'Pengajuan berhasil ditolak');
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
                    $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
                }

                $pengajuan->update([
                    'status' => Pengajuan::REVISI,
                ]);
                if ($pengajuan->mahasiswa->email != '-') {
                    AppHelper::instance()->send_mail([
                        'mail' => $pengajuan->mahasiswa->email,
                        'subject' => 'Pengajuan Tugas Ahir',
                        'title' => 'EKAPTA',
                        'message' => 'Pengajuan Tugas Akhir Anda Berstatus REVISI. Silahkan perbaiki kemudian submit ulang Pengajuan Tugas Ahir anda. <br><br> Catatan Revisi: '.$request->catatan,
                    ]);
                }
                $pengajuan->revisis()->save($revisi);
            }

            return redirect()->route('ta.pengajuan.prodi')->with('success', 'Pengajuan berhasil direvisi');
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

        return back()->with('success','Judul tugas akhir berhasil di update.');
    }
}


