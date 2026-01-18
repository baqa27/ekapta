<DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title }}</title>
        <style>
            * {
                font-size: 12pt;
                margin: 46px 0px 0px 0px;
            }

            .f-14 {
                font-size: 14pt;
            }

            .center {
                margin-left: auto;
                margin-right: auto;
            }

            .image {
                margin-top: 0px;
            }
            #stempel{
                position: relative;
                right: -430px;
                top: -170px;
                opacity: 50%;
            }
        </style>
    </head>

    <body>
        <center>
            <b class="f-14">{{ $title }}</b>
            <br><br><br>
            <b class="f-14">LAPORAN TUGAS AKHIR</b>
            <br><br><br>
            <div style="margin: 0px 50px 0px 50px;">
                <b style="text-transform: uppercase;">{{ $pengajuan->judul }}</b>
            </div>
            <br><br><br><br>
            <span>Disusun Oleh:</span>
            <br>
            <b style="text-transform: uppercase;"><u>{{ $mahasiswa->nama }}</u></b>
            <br>
            <b>{{ $mahasiswa->nim }}</b>
            <br><br><br><br><br>
            <span>Telah disetujui dan disahkan</span>
            <br>
            <span>di Wonosobo, pada tanggal {{ $date }}</span>
            <table class="center">
                <tr>
                    <td style="text-align:center;">
                        <span>Pembimbing Utama</span>
                        <br>
                        <img src="{{ $ttd_dosen_utama }}" class="image" height="80">
                        <br>
                        <span>{{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }}</span>
                        <br>
                        <b>NIDN. {{ $dosen_utama->nidn }}</b>
                    </td>
                    <td style="text-align:center;">
                        <span>Pembimbing Pendamping</span>
                        <br>
                        <img src="{{ $ttd_dosen_pendamping }}" class="image" height="80">
                        <br>
                        <span>{{ $dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar }}</span>
                        <br>
                        <b>NIDN. {{ $dosen_pendamping->nidn }}</b>
                    </td>
                </tr>
            </table>
            <br>
            <table class="center">
                <tr>
                    <td style="text-align:center;">
                        @if ($prodi)
                            <span>Ketua Program Studi</span>
                            <br>
                            <img src="{{ \App\Helpers\AppHelper::instance()->convertImage('storage/app/public/' . substr($prodi->ttd, 31)) }}" class="image" height="80">
                            <br>
                            <span>{{ $prodi->nama . ', ' . $prodi->gelar }}</span>
                            <br>
                            <b>NIDN. {{ $prodi->nidn }}</b>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <span>Dekan</span>
                        <br>
                        <img src="{{ $ttd_dekan }}" class="image" height="80">
                        <br>
                        <span>{{ $dekan->namadekan . ', ' . $dekan->gelar }}</span>
                        <br>
                        <b>NIDN. {{ $dekan->nidn }}</b>
                    </td>
                </tr>
            </table>
        </center>
        <img src="{{ $stempel }}" alt="Stempel Dekan" height="100" id="stempel">
    </body>

    </html>




