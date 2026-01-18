<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <style>
        p {
            margin: 0;
            font-size: 12pt;
            text-align: justify;
            margin-right: 40px
        }

        .margin-left {
            margin-left: 40px;
        }

        .margin-left-right {
            margin-left: 10px;
            margin-right: 10px;
        }

        .text-right {
            position: relative;
            left: 430px;
        }

        .box {
            padding: 10px;
            border: 1px solid black;
            width: 10%;
            position: relative;
            left: 270px;
        }

        .text-ttd {
            position: relative;
            left: 270px;
        }
    </style>
</head>
<body>
    <br><br>
    <center>
        <h4>LEMBAR PERNYATAAN KEASLIAN <br>HASIL TUGAS AKHIR</h4>
    </center>
    <br><br>
    <table>
        <tr>
            <td colspan="3">
                <p class="margin-left">Saya yang bertanda tangan di bawah ini,</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="10"></td>
        </tr>
        <tr>
            <td with="10">
                <p class="margin-left">Nama</p>
            </td>
            <td width="10">
                <p class="margin-left-right">:</p>
            </td>
            <td width="400">
                <p>{{ $mahasiswa->nama }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">NIM</p>
            </td>
            <td>
                <p class="margin-left-right">:</p>
            </td>
            <td>
                <p>{{ $mahasiswa->nim }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="25"></td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="margin-left">Menyatakan bahwa seluruh komponen dan isi dalam Laporan Tugas Akhir ini adalah
                    hasil karya saya sendiri. Apabila dikemudian hari terbukti bahwa dari karya ini saya melakukan
                    plagiarisme, maka saya siap menanggung resiko dan konsekuensi apapun termasuk dicabut dan dibatalkan
                    gelar akademikanya.</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="20"></td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="margin-left">Demikian pernyataan ini saya buat, semoga dapat dipergunakan sebagaimana mestinya
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <br><br><br><br>
                <p class="text-right">Wonosobo, ………………………</p>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>
                <center>
                    <br>
                    <div class="box">
                        <p>Materai <br> 10.000</p>
                    </div>
                    <br>
                    <p class="text-ttd">
                        <u>{{ $mahasiswa->nama }}</u> <br>
                        NIM. {{ $mahasiswa->nim }}
                    </p>
                </center>
            </td>
        </tr>
    </table>
</body>
</html>




