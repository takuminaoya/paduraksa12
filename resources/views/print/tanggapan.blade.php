<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <style>
        .title {
            text-transform: capitalize;
            text-align: center;
            margin: 2rem 0rem 2rem 0rem;
        }

        .t1 {
            font-size: 18px;
            font-weight: bold;
        }

        .title p {
            font-size: 12px;
        }

        .tabler {
            width: 100%;
        }

        .tabler th {
            text-transform: capitalize;
            border: thin solid #CCC;
            font-size: 14px;
            padding: 0.2rem;
        }

        .tabler td {
            text-transform: capitalize;
            border: thin solid #CCC;
            font-size: 12px;
            padding: 0.2rem;
        }
    </style>

    <style>
        .judul {
            font-size: 16px;
        }
        .page-break {
            page-break-after: always;
        }

        .ctl {
            display: block;
            margin: 0 auto;
        }

        header {
            width: 100%;
            text-align: center;
            margin-bottom: 2rem;
        }

        .tanggal {
            text-align: right;
            text-transform: capitalize;
        }

        .body {
            font-size: 14px;
            line-height: 1.3;
            font-family: Arial, Helvetica, sans-serif;
        }

        .detail-pelapor {
            margin-left: 2rem;
        }

        .kata-1 {
            text-indent: 2rem;
            text-align: justify;
            margin-bottom: 1rem;
        }

        .kata-2 {
            text-align: justify;
            text-indent: 2rem;
        }

        .hormat-saya {
            text-align: right;
            text-transform: capitalize;
        }

        table {
            border-collapse: collapse;
        }

        td {
            text-transform: capitalize;
        }

        span {
            font-weight: bold;
            text-transform: capitalize;
        }

        p {
            text-align: justify;
        }

        .lampiran {
            width: 100%;
        }

        .detail-2 {
            margin-top: 1rem;
        }

        .list-tindak-lanjut {
            border-bottom: thin solid #CCC;
            padding-bottom: 0.5rem;
        }

        .tab_ah td {
            padding-right: 0.5rem;
        }

        section {
            margin-left: 2rem;
            margin-right: 2rem;
        }

        .line {
            border-bottom: thin solid #000;
            margin-top: 10px;
        }
    </style>

    <section class="body page-break">
        <header>
            <img class="ctl" src="{{ public_path('storage/images/kop_surat.jpg') }}" width="100%" alt="">
        </header>

        <div class="content" style="width:100%;">
            <p class="tanggal">
                Ungasan, {{ dateReformat($data->getAutorisasiString('TINDAK_LANJUT', 'created_at')) }}
            </p>

            <p class="kepada">

            <table class="tab_ah">
                <tr>
                    <td>Nomor</td>
                    <td>:</td>
                    <td>{{ $data->nomorSurat() }}</td>
                </tr>
                <tr>
                    <td>Sifat</td>
                    <td>:</td>
                    <td>Biasa</td>
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td>:</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Hal</td>
                    <td>:</td>
                    <td><strong>Tindak Lanjut Laporan Paduraksa</strong></td>
                </tr>
            </table>
            </p>

            <p class="kepada">
                Kepada <br>
                Yth. {{ $data->nama }} <br>
                Di -
            <p class="kata-2" style="margin-top: -0.5rem;">
                Tempat
            </p>
            </p>

            <div class="dengan-hormat kata-2">
                Dengan hormat,
            </div>

            <div class="kata-1">
                Menindaklanjuti laporan layanan paduraksa yang saudara laporkan dengan nomor tiket {{ $data->tiket }}
                tanggal {{ dateReformat($data->created_at) }} bahwa dengan ini kami sampaikan tindak lanjut terkait
                dengan laporan yang saudara kirim terlampir dalam lampiran surat ini. Selanjutnya apabila terdapat
                jawaban atau tanggapan atas laporan yang saudara ajukan maka kami Pemerintah Desa bersedia memberikan
                ruang untuk koordinasi bersama untuk menyelesaikan laporan yang saudara ajukan.
            </div>

            <div class="kata-1">
                Demikian surat ini kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terimakasih
            </div>


            <table style="width: 100%;">
                <tr>
                    <td style="width:60%;">&nbsp;</td>
                    <td style="width:40%;">
                        <p class="hormat-saya">
                        <div>Ditetapkan di : Ungasan</div>
                        <img class="ctl" src="{{ public_path('storage/images/tte_new.jpg') }}" style="width:100%;"
                            alt="">
                        </p>
                    </td>
                </tr>
            </table>

            <div>
                Tembusan disampaikan kepada : <br> 
                Arsip
            </div>
        </div>
    </section>

    <section class="body page-break">
        <header>
            <img class="ctl" src="{{ public_path('storage/images/kop_surat.jpg') }}" width="100%" alt="">
        </header>
        <p class="kepada judul">
            Lampiran Surat
        <table class="tab_ah">
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td>{{ $data->nomorSurat() }}</td>
            </tr>
            <tr>
                <td>Hal</td>
                <td>:</td>
                <td><strong>Tindak Lanjut Laporan Paduraksa</strong></td>
            </tr>
        </table>
        </p>

        <div class="content" style="margin-top: 2rem;">
            <table class="tab_ah">
                <tr>
                    <td>Nomor Tiket</td>
                    <td>:</td>
                    <td>{{ $data->tiket }}</td>
                </tr>
                <tr>
                    <td>Klasifikasi Laporan</td>
                    <td>:</td>
                    <td style="text-transform: capitalize;">{{ strtolower(str_replace('_', ' ', $data->klasifikasi)) }}</td>
                </tr>
                <tr>
                    <td>Nama Pelapor</td>
                    <td>:</td>
                    <td>{{ $data->nama }}</td>
                </tr>
                <tr>
                    <td>Nomor Telepon</td>
                    <td>:</td>
                    <td>0{{ $data->no_telpon }}</td>
                </tr>
                <tr>
                    <td>Isi Laporan</td>
                    <td>:</td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="3" style="padding: 0.6rem 0rem;">
                        {{ $data->isi }}
                    </td>
                </tr>
            </table>

            <div class="line"></div>

            Tanggapan atas Laporan : 
            <p>
                {{ $data->getAutorisasiString('TINDAK_LANJUT', 'deskripsi') }}
            </p>
        </div>
    </section>

    <section class="body">
        <header>
            <img class="ctl" src="{{ public_path('storage/images/kop_surat.jpg') }}" width="100%" alt="">
        </header>

        <p class="kepada judul">
            Dokumentasi
        </p>

        <div class="content">
            @php
                $autorisasi = $data->oautorisasi('TINDAK_LANJUT');
                $lampiran = $autorisasi->lampiran;
            @endphp
            @if ($lampiran)
                @php
                    $row = 2;
                    $col = 2;
                @endphp

                <table style="width: 100%;">
                    @for ($i = 0; $i < $row; $i++)
                        <tr>
                            @for ($a = 0; $a < $col; $a++)
                                @if ($i > 0)
                                    @if (array_key_exists($a + 2, $lampiran))
                                        <td style="width: 250px; text-align:center; padding-bottom:10px;"><img class="lampiran" style="width:250px;"
                                                src="{{ public_path('storage/' . $lampiran[$a + 2]) }}"
                                                alt=""></td>
                                    @endif
                                @else
                                    @if (array_key_exists($a, $lampiran))
                                        <td style="width: 250px; text-align:center; padding-bottom:10px;"><img class="lampiran" style="width:250px;"
                                                src="{{ public_path('storage/' . $lampiran[$a]) }}"
                                                alt=""></td>
                                    @endif
                                @endif
                            @endfor
                        </tr>
                    @endfor
                </table>
            @else
                <h5 style="text-align: center; font-weight:bold; text-transform:uppercase;">Tidak Ada Lampiran</h5>
            @endif
        </div>
    </section>
</body>

</html>
