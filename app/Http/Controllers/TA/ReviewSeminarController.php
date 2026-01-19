<?php

namespace App\Http\Controllers\TA;

use App\Helpers\AppHelper;
use App\Models\TA\ReviewSeminar;
use App\Models\TA\RevisiReviewSeminar;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewSeminarController extends \App\Http\Controllers\Controller
{
    public function edit($id)
    {
        $review_seminar = ReviewSeminar::findOrFail($id);

        if ($review_seminar->status == 'review' || $review_seminar->status == 'diterima'){
            return back();
        }

        $data = [
            'title' => 'Submit Laporan Proposal',
            'active' => 'seminar-ta',
            'module' => 'ta',
            'review' => $review_seminar,
        ];

        return view('ta.pages.mahasiswa.seminar.submit-review', $data);
    }

    public function update(Request $request)
    {
        $review_seminar = ReviewSeminar::findOrFail($request->id);

        if ($review_seminar->status == 'review' || $review_seminar->status == 'diterima'){
            return back();
        }

        $validatedData = $request->validate([
           'lampiran' => ['required', 'mimes:pdf, docx', 'max:5000'],
        ]);

        $validatedData['keterangan'] = $request->keterangan;
        $validatedData['status'] = ReviewSeminar::REVIEW;

        if ($review_seminar->lampiran){
            //AppHelper::instance()->deleteLampiran($review_seminar->lampiran);
        }

        $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran,'lampirans');

        $review_seminar->update($validatedData);

        return redirect('seminar/reviews/'.$review_seminar->seminar->id)->with('success','Laporan proposal berhasil disubmit.');
    }

    public function reviewDosen($id)
    {
        $review_seminar = ReviewSeminar::findOrFail($id);

        $is_dosen_penguji_utama = $review_seminar->seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->first();

        $form_status = false;
        if ($is_dosen_penguji_utama){
            if ($is_dosen_penguji_utama->id == $review_seminar->id){
                $form_status = true;
            }
        }

        $data = [
            'title' => 'Review Seminar TA',
            'active' => 'seminar-ta',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarDosen',
            'module' => 'ta',
            'review_seminar' => $review_seminar,
            'revisis' => $review_seminar->revisis()->orderBy('created_at','desc')->paginate(5),
            'form_status' => $form_status ? 1 : 0,
        ];

        return view('ta.pages.dosen.seminar.review', $data);
    }
    public function revisiStore(Request $request)
    {
        $review_seminar = ReviewSeminar::findOrFail($request->id);

        $revisi = new RevisiReviewSeminar();
        $revisi->keterangan = $request->catatan; // Database uses 'keterangan' column
        $request->validate([
            'lampiran' => [
                Rule::requiredIf(function () {
                    if (empty($this->request->lampiran)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,docx', 'max:5000'
            ]
        ]);

        if ($request->file('lampiran')) {
            //$revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
        }
        $revisi->lampiran = $review_seminar->lampiran ? $review_seminar->lampiran : $review_seminar->seminar->lampiran_3;

        if ($review_seminar->status == ReviewSeminar::REVIEW) {
            $review_seminar->update([
                'status' => ReviewSeminar::REVISI,
            ]);
            $review_seminar->revisis()->save($revisi);
            if ($review_seminar->seminar->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $review_seminar->seminar->mahasiswa->email,
                    'subject' => 'Seminar Tugas Ahir',
                    'title' => 'EKAPTA',
                    'message' => 'Seminar Tugas Akhir Anda Berstatus REVISI. Silahkan perbaiki kemudian lakukan submit ulang!. <br><br>Catatan revisi: '.$request->catatan,
                ]);
            }
            return back()->with('success', 'Revisi berhasil ditambahkan.');
        } elseif ($review_seminar->status == ReviewSeminar::REVISI) {
            $review_seminar->revisis()->save($revisi);
            return back()->with('success', 'Revisi berhasil ditambahkan.');
        }
    }

    public function revisiDelete(Request $request)
    {
        $revisi = RevisiReviewSeminar::findOrFail($request->id);
        //AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function reviewAcc(Request $request)
    {
        $review_seminar = ReviewSeminar::findOrFail($request->id);

        $revisi = new RevisiReviewSeminar();
        $revisi->keterangan = $request->catatan; // Database uses 'keterangan' column
        $revisi->lampiran = $review_seminar->lampiran ? $review_seminar->lampiran : $review_seminar->seminar->lampiran_3;
        $review_seminar->update([
            'status' => ReviewSeminar::DITERIMA,
            'tanggal_acc' => $request->type ? $review_seminar->tanggal_acc_manual: now(),
        ]);
        $review_seminar->revisis()->save($revisi);
        if ($review_seminar->seminar->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $review_seminar->seminar->mahasiswa->email,
                'subject' => 'Seminar Tugas Ahir',
                'title' => 'EKAPTA',
                'message' => 'Selamat Seminar Tugas Akhir Anda Berstatus DITERIMA.',
            ]);
        }
        return back()->with('success','Seminar TA berhasil di Acc.');
    }

    public function reviewCancelAcc(Request $request)
    {
        $review_seminar = ReviewSeminar::findOrFail($request->id);

        $review_seminar->update([
            'status' => ReviewSeminar::REVIEW,
            'tanggal_acc' => null,
        ]);

        return back()->with('success','Seminar TA berhasil di Cancel Acc.');
    }

    public function reviewNilai(Request $request)
    {
        $review_seminar = ReviewSeminar::findOrFail($request->id);

        if ($request->is_lulus){
            $review_seminar->seminar->update([
                'is_lulus' => $request->is_lulus,
            ]);
        }

        $review_seminar->update([
            'nilai_1' => $request->nilai_1,
            'nilai_2' => $request->nilai_2,
            'nilai_3' => $request->nilai_3,
            'nilai_4' => $request->nilai_4,
            'status' => ReviewSeminar::DITERIMA,
        ]);

        return back()->with('success','Nilai Seminar TA berhasil disimpan');
    }

    public function submitManual($id)
    {
        $review_seminar = ReviewSeminar::findOrFail($id);
        if ($review_seminar->status == 'revisi' || $review_seminar->status == 'diterima'){
            return back();
        }

        $data = [
            'title' => 'Submit Acc Manual',
            'active' => 'seminar-ta',
            'module' => 'ta',
            'review' => $review_seminar,
        ];

        return view('ta.pages.mahasiswa.seminar.submit-acc-manual', $data);
    }

    public function submitManualStore(Request $request, $id)
    {
        $review_seminar = ReviewSeminar::findOrFail($id);
        if ($review_seminar->status == 'revisi' || $review_seminar->status == 'diterima'){
            return back();
        }

        $validatedData = $request->validate([
            'lampiran_lembar_revisi' => ['required', 'mimes:pdf, jpg,jpeg,png', 'max:5000'],
            'tanggal_acc_manual' => 'required',
         ]);
         $validatedData['lampiran_lembar_revisi'] = AppHelper::instance()->uploadLampiran($request->lampiran_lembar_revisi,'lampirans');
        $review_seminar->update($validatedData);

        return redirect()->route('seminar.reviews', $review_seminar->seminar->id);
    }

    public function updateNilai(Request $request)
    {
        $request->validate([
            'review_id' => 'required|integer',
            'field_name' => 'required|string',
            'field_value' => 'required|integer',
        ]);

        $review = ReviewSeminar::find($request->review_id);
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


