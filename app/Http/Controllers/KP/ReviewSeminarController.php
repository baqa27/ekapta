<?php

namespace App\Http\Controllers\KP;

use App\Helpers\AppHelper;

// Import KP Models
use App\Models\KP\ReviewSeminar;
use App\Models\KP\RevisiReviewSeminar;

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
            'active' => 'seminar-kp',
            'review' => $review_seminar,
        ];

        return view('kp.pages.mahasiswa.seminar.submit-review', $data);
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

        $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran,'lampirans', $review_seminar->seminar->mahasiswa->nim, 'seminar');



        $review_seminar->update($validatedData);

        return redirect()->route('kp.seminar.reviews', $review_seminar->seminar->id)->with('success','Laporan proposal berhasil disubmit.');
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
            'active' => 'seminar-kp',
            'sidebar' => 'kp.partials.sidebarDosen',
            'module' => 'kp',
            'review_seminar' => $review_seminar,
            'revisis' => $review_seminar->revisis()->orderBy('created_at','desc')->paginate(5),
            'form_status' => $form_status ? 1 : 0,
        ];

        return view('kp.pages.dosen.seminar.review', $data);
    }
    public function revisiStore(Request $request)
    {
        $review_seminar = ReviewSeminar::findOrFail($request->id);

        $revisi = new RevisiReviewSeminar();
        $revisi->catatan = $request->catatan;
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


        $revisi->lampiran = $review_seminar->lampiran ? $review_seminar->lampiran : $review_seminar->seminar->lampiran_3;

        if ($review_seminar->status == ReviewSeminar::REVIEW) {
            $review_seminar->update([
                'status' => ReviewSeminar::REVISI,
            ]);
            $review_seminar->revisis()->save($revisi);
            if ($review_seminar->seminar->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $review_seminar->seminar->mahasiswa->email,
                    'subject' => 'Seminar Kerja Praktek',
                    'title' => 'EKAPTA',
                    'message' => 'Seminar Kerja Praktek Anda Berstatus REVISI. Silahkan perbaiki kemudian lakukan submit ulang!. <br><br>Catatan revisi: '.$request->catatan,
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

        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function reviewAcc(Request $request)
    {
        $review_seminar = ReviewSeminar::findOrFail($request->id);

        $revisi = new RevisiReviewSeminar();
        $revisi->catatan = $request->catatan;
        $revisi->lampiran = $review_seminar->lampiran ? $review_seminar->lampiran : $review_seminar->seminar->lampiran_3;
        $review_seminar->update([
            'status' => ReviewSeminar::DITERIMA,
            'tanggal_acc' => $request->type ? $review_seminar->tanggal_acc_manual: now(),
        ]);
        $review_seminar->revisis()->save($revisi);
        if ($review_seminar->seminar->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $review_seminar->seminar->mahasiswa->email,
                'subject' => 'Seminar Kerja Praktek',
                'title' => 'EKAPTA',
                'message' => 'Selamat Seminar Kerja Praktek Anda Berstatus DITERIMA.',
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
            'active' => 'seminar-kp',
            'review' => $review_seminar,
        ];

        return view('kp.pages.mahasiswa.seminar.submit-acc-manual', $data);
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
         $validatedData['lampiran_lembar_revisi'] = AppHelper::instance()->uploadLampiran($request->lampiran_lembar_revisi,'lampirans', $review_seminar->seminar->mahasiswa->nim, 'seminar');
        $review_seminar->update($validatedData);

        return redirect()->route('kp.seminar.reviews', $review_seminar->seminar->id);
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
    public function reviewPublic($token)
    {
        $review_seminar = ReviewSeminar::where('token', $token)->firstOrFail();

        // Prevent review if already accepted (optional, but good practice)
        if ($review_seminar->status == 'diterima') {
             // You might want to show a read-only view or a message

        }

        $is_dosen_penguji_utama = $review_seminar->seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->first();

        $form_status = false;
        if ($is_dosen_penguji_utama){
            if ($is_dosen_penguji_utama->id == $review_seminar->id){
                $form_status = true;
            }
        }

        $data = [
            'title' => 'Review Seminar KP (Public)',
            'active' => 'seminar-kp',
            'review_seminar' => $review_seminar,
            'revisis' => $review_seminar->revisis()->orderBy('created_at','desc')->paginate(5),
            'form_status' => $form_status ? 1 : 0,
        ];

        return view('kp.pages.public.review-seminar', $data);
    }

    public function storePublic(Request $request, $token)
    {
        $review_seminar = ReviewSeminar::where('token', $token)->firstOrFail();

        // Validation similar to reviewNilai but adapted
        $validatedData = $request->validate([
            'nilai_1' => 'required|numeric|min:0|max:100',
            'nilai_2' => 'required|numeric|min:0|max:100',
            'nilai_3' => 'required|numeric|min:0|max:100',
            'nilai_4' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:diterima,revisi',
        ]);
        
        // Handling Status (Acc or Revisi)
        // If Revisi, we might need a separate revision logic or just update status+catatan
        // Simplified flow: Dosen submits grades + status.

        if ($request->status == 'revisi') {
             $revisi = new RevisiReviewSeminar();
             $revisi->catatan = $request->keterangan ?? '-';
             // Attachment logic if needed, skipping for public simplicity or add if required
             $revisi->lampiran = $review_seminar->lampiran ? $review_seminar->lampiran : $review_seminar->seminar->lampiran_3;
             
             $review_seminar->update([
                'status' => ReviewSeminar::REVISI,
             ]);
             $review_seminar->revisis()->save($revisi);
             
             return back()->with('success', 'Review (Revisi) berhasil dikirim.');

        } else {
            // DITERIMA
            $review_seminar->update([
                'nilai_1' => $request->nilai_1,
                'nilai_2' => $request->nilai_2,
                'nilai_3' => $request->nilai_3,
                'nilai_4' => $request->nilai_4,
                'status' => ReviewSeminar::DITERIMA,

            ]);
            
            // If Dosen Penguji Utama decides Lulus/Tidak (if implemented)
            if ($request->has('is_lulus')) {
                 $review_seminar->seminar->update([
                    'is_lulus' => $request->is_lulus,
                ]);
            }

            return back()->with('success', 'Nilai Seminar KP berhasil disimpan.');
        }
    }
}

