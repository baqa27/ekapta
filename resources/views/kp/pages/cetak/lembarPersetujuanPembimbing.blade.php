<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            font-family: 'Times New Roman', Times, serif;
        }

        body {
            font-size: 11pt;
        }

        p {
            font-size: 11pt;
            line-height: 1.6;
        }

        .content-section {
            margin-left: 60px;
            margin-right: 60px;
        }

        .label-col {
            width: 165px;
            vertical-align: top;
        }

        .colon-col {
            width: 15px;
            vertical-align: top;
        }

        .value-col {
            vertical-align: top;
        }

        .signature-table {
            width: 100%;
            margin-top: 15px;
        }

        .signature-table td {
            padding: 10px 20px;
            text-align: center;
            vertical-align: top;
            width: 50%;
        }

        .text-right {
            text-align: right;
        }

        u {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td>
                <img src="{{ $kop_surat }}" alt="Kop Surat" height="151">
            </td>
        </tr>
        <tr>
            <td height="40">
                <center>
                    <h4 style="text-decoration: underline;">LEMBAR PERSETUJUAN</h4>
                    <h4>CALON DOSEN PEMBIMBING KERJA PRAKTEK (KP)</h4>
                </center>
            </td>
        </tr>
    </table>

    <br>

    <div class="content-section">
        <p>Yang bertanda tangan di bawah ini</p>
        <table style="width: 100%; margin-top: 5px;">
            <tr>
                <td class="label-col">Nama Dosen Pembimbing</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $dosen_utama ? $dosen_utama->nama.', '.$dosen_utama->gelar : '......................................' }}</td>
            </tr>
            <tr>
                <td class="label-col">NIDN</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $dosen_utama ? $dosen_utama->nidn : '......................................' }}</td>
            </tr>
            <tr>
                <td class="label-col">Menyatakan</td>
                <td class="colon-col">:</td>
                <td class="value-col"><b>Bersedia / Tidak Bersedia</b> <i>(coret yang tidak sesuai)</i></td>
            </tr>
        </table>

        <br>

        <p>Untuk menjadi Pembimbing Kerja Praktek (KP) mahasiswa berikut :</p>
        <table style="width: 100%; margin-top: 5px;">
            <tr>
                <td class="label-col">Nama</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $mahasiswa->nama }}</td>
            </tr>
            <tr>
                <td class="label-col">NIM</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $mahasiswa->nim }}</td>
            </tr>
            <tr>
                <td class="label-col">Program Studi</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $mahasiswa->prodi }}</td>
            </tr>
            <tr>
                <td class="label-col">Judul Kerja Praktek</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $pengajuan->judul }}</td>
            </tr>
            <tr>
                <td class="label-col">Lokasi Kerja Praktek</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $pengajuan->lokasi_kp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Catatan Pembimbing</td>
                <td class="colon-col">:</td>
                <td class="value-col">……………………………………………………………………………………</td>
            </tr>
            <tr>
                <td class="label-col"></td>
                <td class="colon-col"></td>
                <td class="value-col">……………………………………………………………………………………</td>
            </tr>
            <tr>
                <td class="label-col"></td>
                <td class="colon-col"></td>
                <td class="value-col">……………………………………………………………………………………</td>
            </tr>
            <tr>
                <td class="label-col"></td>
                <td class="colon-col"></td>
                <td class="value-col">……………………………………………………………………………………</td>
            </tr>
        </table>

        <br><br><br>

        <p class="text-right">Wonosobo, ………………………………</p>

        <br>

        <table class="signature-table">
            <tr>
                <td>
                    <p>Mengetahui,</p>
                    <p>Kaprodi {{ $prodi->namaprodi }}</p>
                    <br><br><br><br>
                    @if (\App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi))
                    <p><b><u>{{ \App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi)->nama.', '.\App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi)->gelar }}</u></b></p>
                    <p>NIDN. {{ \App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi)->nidn }}</p>
                    @else
                    <p><b><u>........................................</u></b></p>
                    <p>NIDN. ........................</p>
                    @endif
                </td>
                <td>
                    <p>Menyetujui,</p>
                    <p>Calon Pembimbing</p>
                    <br><br><br><br>
                    @if($dosen_utama)
                    <p><b><u>{{ $dosen_utama->nama.', '.$dosen_utama->gelar }}</u></b></p>
                    <p>NIDN. {{ $dosen_utama->nidn }}</p>
                    @else
                    <p><b><u>........................................</u></b></p>
                    <p>NIDN. ........................</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>

</body>
</html>




