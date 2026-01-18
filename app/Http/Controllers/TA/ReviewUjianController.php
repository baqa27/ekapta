<?php

namespace App\Http\Controllers\TA;

use App\Helpers\AppHelper;
use App\Models\TA\ReviewUjian;
use App\Models\TA\RevisiReviewUjian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewUjianController extends \App\Http\Controllers\Controller
{

    public function edit($id)
    {
        $review_ujian = ReviewUjian::findOrFail($id);

        if ($review_ujian->status == 'review' || $review_ujian->status == 'diterima') {
            return back();
        }

        $data = [
            'title' => 'Submit Laporan Proposal',
            'active' => 'ujian-ta',
            'module' => 'ta',
            'review' => $review_ujian,
        ];

        return view('ta.pages.mahasiswa.ujian.submit-review', $data);
    }

    public function update(Request $request)
    {
        $review_ujian = ReviewUjian::findOrFail($request->id);

        if ($review_ujian->status == 'review' || $review_ujian->status == 'diterima') {
            return back();
        }

        $validatedData = $request->validate([
            'lampiran' => ['required', 'mimes:pdf, docx', 'max:5000'],
        ]);

        $validatedData['keterangan'] = $request->keterangan;
        $validatedData['status'] = ReviewUjian::REVIEW;

        if ($review_ujian->lampiran) {
            //AppHelper::instance()->deleteLampiran($review_ujian->lampiran);
        }

        $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');

        $review_ujian->update($validatedData);

        return redirect('ujian/reviews/' . $review_ujian->ujian->id)->with('success', 'Laporan proposal berhasil disubmit.');
    }

    public function reviewDosen($id)
    {
        $review_ujian = ReviewUjian::findOrFail($id);

        $is_dosen_penguji_utama = $review_ujian->ujian->reviews()->where('dosen_status', ReviewUjian::DOSEN_PENGUJI)->first();

        $form_status = false;
        if ($is_dosen_penguji_utama) {
            if ($is_dosen_penguji_utama->id == $review_ujian->id) {
                $form_status = true;
            }
        }

        $data = [
            'title' => 'Review Ujian TA',
            'active' => 'ujian-ta',
            'module' => 'ta',
            'sidebar' => 'ta.partials.sidebarDosen',
            'module' => 'ta',
            'review_ujian' => $review_ujian,
            'revisis' => $review_ujian->revisis()->orderBy('created_at', 'desc')->paginate(5),
            'form_status' => $form_status ? 1 : 0,
        ];

        return view('ta.pages.dosen.ujian.review', $data);
    }
    public function revisiStore(Request $request)
    {
        $review_ujian = ReviewUjian::findOrFail($request->id);

        $revisi = new RevisiReviewUjian();
        $request->validate([
            'lampiran' => [
                Rule::requiredIf(function () use ($request) {
                    if (empty($request->lampiran)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,docx', 'max:5000'
            ]
        ]);
        $revisi->catatan = $request->catatan;

        if ($request->file('lampiran')) {
            //$revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
        }
        $revisi->lampiran = $review_ujian->lampiran ? $review_ujian->lampiran : $review_ujian->ujian->lampiran_laporan;

        if ($review_ujian->status == ReviewUjian::REVIEW) {
            $review_ujian->update([
                'status' => ReviewUjian::REVISI,
            ]);
            $review_ujian->revisis()->save($revisi);
            if ($review_ujian->ujian->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $review_ujian->ujian->mahasiswa->email,
                    'subject' => 'Ujian Tugas Ahir',
                    'title' => 'EKAPTA',
                    'message' => 'Ujian Tugas Akhir Anda Berstatus REVISI. Silahkan perbaiki kemudian lakukan submit ulang!. <br><br>Catatan revisi: ' . $request->catatan,
                ]);
            }
            return back()->with('success', 'Revisi berhasil ditambahkan.');
        } elseif ($review_ujian->status == ReviewUjian::REVISI) {
            $review_ujian->revisis()->save($revisi);
            return back()->with('success', 'Revisi berhasil ditambahkan.');
        }
    }

    public function revisiDelete(Request $request)
    {
        $revisi = RevisiReviewUjian::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function reviewAcc(Request $request)
    {
        $review_ujian = ReviewUjian::findOrFail($request->id);

        $revisi = new RevisiReviewUjian();
        $revisi->catatan = $request->catatan;
        $revisi->lampiran = $review_ujian->lampiran ? $review_ujian->lampiran : $review_ujian->ujian->lampiran_laporan;
        $review_ujian->update([
            'status' => ReviewUjian::DITERIMA,
            'tanggal_acc' => $request->type ? $review_ujian->tanggal_acc_manual : now(),
        ]);
        $review_ujian->revisis()->save($revisi);
        if ($review_ujian->ujian->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $review_ujian->ujian->mahasiswa->email,
                'subject' => 'Ujian Tugas Ahir',
                'title' => 'EKAPTA',
                'message' => 'Selamat Ujian Tugas Akhir Anda Berstatus DITERIMA',
            ]);
        }
        return back()->with('success', 'Ujian TA berhasil di Acc.');
    }

    public function reviewCancelAcc(Request $request)
    {
        $review_ujian = ReviewUjian::findOrFail($request->id);

        $review_ujian->update([
            'status' => ReviewUjian::REVIEW,
            'tanggal_acc' => null,
        ]);

        return back()->with('success', 'Ujian TA berhasil di Cancel Acc.');
    }

    public function reviewNilai(Request $request)
    {
        $review_ujian = ReviewUjian::findOrFail($request->id);

        if ($request->is_lulus) {
            $review_ujian->ujian->update([
                'is_lulus' => $request->is_lulus,
            ]);
        }

        $review_ujian->update([
            'nilai_1' => $request->nilai_1,
            'nilai_2' => $request->nilai_2,
            'nilai_3' => $request->nilai_3,
            'nilai_4' => $request->nilai_4,
            'status' => ReviewUjian::DITERIMA,
        ]);

        return back()->with('success', 'Nilai Ujian TA berhasil disimpan');
    }

    public function submitManual($id)
    {
        $review_ujian = ReviewUjian::findOrFail($id);
        if ($review_ujian->status == 'revisi' || $review_ujian->status == 'diterima') {
            return back();
        }

        $data = [
            'title' => 'Submit Acc Manual',
            'active' => 'ujian-ta',
            'module' => 'ta',
            'review' => $review_ujian,
        ];

        return view('ta.pages.mahasiswa.ujian.submit-acc-manual', $data);
    }

    public function submitManualStore(Request $request, $id)
    {
        $review_ujian = ReviewUjian::findOrFail($id);
        if ($review_ujian->status == 'revisi' || $review_ujian->status == 'diterima') {
            return back();
        }

        $validatedData = $request->validate([
            'lampiran_lembar_revisi' => ['required', 'mimes:pdf, jpg,jpeg,png', 'max:5000'],
            'tanggal_acc_manual' => 'required',
        ]);
        $validatedData['lampiran_lembar_revisi'] = AppHelper::instance()->uploadLampiran($request->lampiran_lembar_revisi, 'lampirans');
        $review_ujian->update($validatedData);

        return redirect()->route('ujian.reviews', $review_ujian->ujian->id);
    }

    public function updateNilai(Request $request)
    {
        $request->validate([
            'review_id' => 'required|integer',
            'field_name' => 'required|string',
            'field_value' => 'required|integer',
        ]);

        $review = ReviewUjian::find($request->review_id);
        if ($review) {
            $fieldName = $request->field_name;
            $review->$fieldName = $request->field_value;
            $review->status = 'diterima';
            $review->save();

            $nilai_akhir = AppHelper::instance()->hitung_nilai_mean(
                $review->nilai_1,
                $review->nilai_2,
                $review->nilai_3,
                $review->nilai_4
            );

            return response()->json(['message' => 'Nilai berhasil diperbarui', 'nilai_akhir' => $nilai_akhir]);
        } else {
            return response()->json(['message' => 'Review tidak ditemukan'], 404);
        }
    }
}


