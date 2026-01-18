@php
$bulan = null;
if ($date->format('m') == '01'){
$bulan = 'I';
}elseif ($date->format('m') == '02'){
$bulan = 'II';
}elseif ($date->format('m') == '03'){
$bulan = 'III';
}elseif ($date->format('m') == '04'){
$bulan = 'IV';
}elseif ($date->format('m') == '05'){
$bulan = 'V';
}elseif ($date->format('m') == '06'){
$bulan = 'VI';
}elseif ($date->format('m') == '07'){
$bulan = 'VII';
}elseif ($date->format('m') == '08'){
$bulan = 'VIII';
}elseif ($date->format('m') == '09'){
$bulan = 'IX';
}elseif ($date->format('m') == '10'){
$bulan = 'X';
}elseif ($date->format('m') == '11'){
$bulan = 'XI';
}elseif ($date->format('m') == '12'){
$bulan = 'XII';
}
@endphp
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
        }

        .margin-left {
            margin-left: 80px;
        }

        p {
            font-size: 12pt;
            text-align: justify
        }

        .margin-right {
            margin-right: 40px;
        }

        .margin-top {
            position: relative;
            top: 10px;
        }

        .titik-dua {
            margin-left: 10px;
            margin-right: 10px;
        }

        .top {
            position: relative;
            top: -24px;
        }

        .text-keterangan {
            position: relative;
            left: 495px;
        }

        #qr-code {
            margin-top: -40px;
            margin-left: 670px;
        }

        .text-expired {
            margin-left: 80px;
            color: rgb(255, 89, 191);
        }

        .d-flex {
            display: flex;
        }

        #stempel {
            opacity: 20%;
            position: relative;
            top: 10px;
            right: -10px;
        }

        #ttd {
            position: relative;
            left: -80px;
            max-width: 180px;
            top: -30px;
        }

        #detail-dekan {
            position: relative;
            top: -70px
        }

        .text-expired {
            position: relative;
            top: -50px;
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
            <td>
                <center>
                    <h3>SURAT TUGAS PEMBIMBINGAN KERJA Praktek <br>
                        No. {{ $no_urut }}/ST.KP/FASTIKOM-UNSIQ/{{ $bulan }}/{{ $date->format('Y') }}
                    </h3>
                    <br>
                </center>
            </td>
        </tr>
    </table>
    <img src="{{ $qr_code_bimbingan }}" alt="QR Code" height="80" id="qr-code">
    <table>
        <tr>
            <td colspan="3">
                <p class="margin-left"><b><i>Assalamu'alaikum Wr. Wb.</i></b> </p>
                <br>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="margin-left margin-right">Dekan Fakultas Teknik dan Ilmu Komputer (FASTIKOM) Universitas Sains
                    Al-Qur'an
                    (UNSIQ) Jawa Tengah di Wonosobo, memberikan tugas kepada:</p>
                <br>
            </td>
        </tr>
        <tr>
            <td width="180">
                <p class="margin-left">Nama</p>
            </td>
            <td width="20">
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p class="margin-top">{{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }} <br>
                    (Selaku Dosen Pembimbing KP)
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <br>
                <p class="margin-left">Untuk memberikan bimbingan Kerja Praktek kepada mahasiswa tersebut
                    dibawah ini:</p>
                <br>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">Nama</p>
            </td>
            <td>
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p>{{ $mahasiswa->nama }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">NIM</p>
            </td>
            <td>
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p>{{ $mahasiswa->nim }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">Program Studi</p>
            </td>
            <td>
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p>{{ $mahasiswa->prodi }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">Tanggal Pembayaran</p>
            </td>
            <td>
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p>{{ $pendaftaran->tanggal_pembayaran }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">Judul Kerja Praktek</p>
            </td>
            <td>
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p class="margin-right"><b>{{ $pendaftaran->pengajuan->judul }}</b></p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">Masa Berlaku</p>
            </td>
            <td>
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p>{{ $dateLocale }} s/d {{ $date_expired }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="margin-left margin-right">Selama melakukan pembimbingan, harus dilaksanakan dengan
                    sungguh-sungguh dan
                    tidak menyimpang dari kaidah keilmuannya. Pembimbingan KP maksimal dilakukan selama 6
                    bulan (1 Semester). Jika sampai batas waktu yang telah ditentukan mahasiswa tersebut belum
                    menyelesaikan KP, maka KP tersebut dianggap gugur dan mahasiswa harus mengambil
                    judul KP yang berbeda dari judul sebelumnya.</p>
                <br>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="margin-left"><b><i>Wassalamu'alakum Wr. Wb.</i></b></p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="text-keterangan">Wonosobo,
                    {{ $dateLocale }}</p>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="290"></td>
            <td width="290">
                <center>
                    <span>
                        Dekan <br>
                        <div class="d-flex" style="height: 180px">
                            @if($stempel)
                                <img src="{{ $stempel }}" alt="Stempel Dekan" height="180" id="stempel">
                            @endif
                            @if($ttd_dekan)
                                <img src="{{ $ttd_dekan }}" alt="TTD Dekan" height="110" id="ttd">
                            @endif
                        </div>
                        <div id="detail-dekan">
                            @if($dekan)
                                <b><u>{{ $dekan->namadekan . ', ' . $dekan->gelar }}</u></b><br>
                                <b>NPU. {{ $dekan->nidn }}</b>
                            @else
                                <b><u>____________________</u></b><br>
                                <b>NPU. ____________________</b>
                            @endif
                        </div>
                    </span>
                </center>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p class="text-expired">
                    <b><i>NB. BATAS MAKSIMAL SAMPAI PADA : {{ $date_expired }}</i></b>
                </p>
            </td>
        </tr>
    </table>

     <table>
        <tr>
            <td>
                <img src="{{ $kop_surat }}" alt="Kop Surat" height="151">
            </td>
        </tr>
        <tr>
            <td>
                <center>
                    <h3>LEMBAR BIMBINGAN KERJA Praktek (KP)</h3>
                    <br>
                </center>
            </td>
        </tr>
    </table>
    <img src="{{ $qr_code_bimbingan }}" alt="QR Code" height="80" id="qr-code">

    <table class="margin-left">
        <tr>
            <td width="120">NAMA</td>
            <td width="2">:</td>
            <td width="350">{{ $mahasiswa->nama }}</td>
        </tr>
        <tr>
            <td>NIM</td>
            <td width="2">:</td>
            <td>{{ $mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td>PRODI</td>
            <td width="2">:</td>
            <td>{{ $prodi->namaprodi }}</td>
        </tr>
        <tr>
            <td style="text-align: left;vertical-align: top;">JUDUL KP</td>
            <td width="2" style="text-align: left;vertical-align: top;">:</td>
            <td>{{ $pengajuan->judul }}</td>
        </tr>
        <tr>
            <td>DOSEN PEMBIMBING</td>
            <td width="2">:</td>
            <td>{{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }}</td>
        </tr>
        <tr>
            <td>NO SURAT TUGAS</td>
            <td width="2">:</td>
            <td>{{ $no_urut }}/ST.KP/FASTIKOM-UNSIQ/{{$bulan}}/{{ $date->format('Y') }}
            </td>
        </tr>
        <tr>
            <td>MASA BERLAKU</td>
            <td width="2">:</td>
            <td>{{ $dateLocale }} s/d {{ $date_expired }}</td>
        </tr>
    </table>

    <table class="margin-left table-border" style="border: 1px solid black; border-collapse: collapse;height: 670px;">
        <tr style="background-color: gray">
            <td width="20" style="text-align:center; border: 1px solid black; border-collapse: collapse;">No</td>
            <td width="100" style="text-align:center; border: 1px solid black; border-collapse: collapse;">TANGGAL
            </td>
            <td width="250" style="text-align:center; border: 1px solid black; border-collapse: collapse;">KETERANGAN
            </td>
            <td width="100" style="text-align:center; border: 1px solid black; border-collapse: collapse;">TANDA
                TANGAN</td>
        </tr>
       <tr>
            <td height="500" style="border: 1px solid black; border-collapse: collapse;"></td>
            <td style="border: 1px solid black; border-collapse: collapse;"></td>
            <td style="border: 1px solid black; border-collapse: collapse;"></td>
            <td style="border: 1px solid black; border-collapse: collapse;"></td>
        </tr>
    </table>

</body>

</html>




