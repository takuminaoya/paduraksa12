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
        }

        .detail-pelapor {
            margin-left: 2rem;
        }

        .kata-1 {
            text-indent: 2rem;
            margin-bottom: 1rem;
        }

        .kata-2 {
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
    </style>

    <section class="body page-break">
        <header>
            <img class="ctl" src="{{ public_path('storage/images/banner.jpg') }}" width="30%" alt="">
        </header>

        <div class="content" style="width:100%;">
            <p class="tanggal">
                Nomor: {{ $data->nomorSurat() }} <br>
                Lampiran: – <br>
                Perihal: Tanggapan atas Laporan Masyarakat <br>
            </p>

            <p class="kepada">
                Kepada <br>
                Yth. Bapak/Ibu {{ $data->nama }} <br>
                Di Tempat <br>
            </p>

            <div class="dengan-hormat">
                Dengan hormat,
            </div>

            <div class="detail-2">
                Menindaklanjuti laporan yang telah Bapak/Ibu sampaikan pada tanggal
                <span>{{ dateReformat($data->created_at) }}</span> mengenai <span>{{ $data->judul }}</span>, bersama
                ini kami
                sampaikan bahwa:
            </div>

            <div class="daftar">
                <ol>
                    <li>Laporan telah kami terima dan verifikasi sesuai dengan ketentuan yang berlaku.</li>
                    <li>Tim pelaksana dari Pemerintah Desa Ungasan, Pada Banjar/Dusun
                        <span>{{ strtolower($data->banjar_kejadian) }}</span>, bersama pihak terkait, telah melakukan
                        peninjauan dan penanganan langsung di lokasi.</li>
                    <li>
                        Proses penanganan telah selesai dilaksanakan pada tanggal
                        <span>{{ dateReformat($data->getAutorisasiString('SELESAI', 'tanggal_autorisasi')) }}</span>, dengan
                        rincian
                        tindakan sebagai berikut:

                        @if (count($data->getAutorisasiLaporan('TINDAK_LANJUT')) > 0)
                            <ul>
                                @foreach ($data->getAutorisasiLaporan('TINDAK_LANJUT') as $item)
                                    <li><span>{{ $item->judul }}</span> dilaksanakan pada tanggal
                                        <span>{{ dateReformat($item->tanggal) }}</span>.</li>
                                @endforeach
                            </ul>
                        @else
                            <ul>
                                <li>Tidak Ada Laporan Tindakan</li>
                            </ul>
                        @endif

                    </li>
                </ol>
            </div>

            <div class="kata-1">
                Dengan demikian, kami menyatakan bahwa laporan tersebut telah selesai ditindaklanjuti dan ditutup dalam
                administrasi pengaduan desa.
            </div>

            <div class="kata-1">
                Kami mengucapkan terima kasih atas partisipasi Bapak/Ibu dalam menjaga dan meningkatkan kualitas
                pelayanan
                serta lingkungan di wilayah Desa Ungasan. Kami selalu terbuka untuk menerima saran, masukan, maupun
                laporan lainnya di masa mendatang.
            </div>

            <div class="kata-1">
                Demikian surat tanggapan ini kami sampaikan. Atas perhatian dan kerja sama Bapak/Ibu, kami ucapkan
                terima
                kasih.
            </div>


            <p class="hormat-saya">
                Hormat kami, <br>
                Perbekel Desa Ungasan <br>
            </p>

        </div>
    </section>

    <section class="body">
        <header>
            <img class="ctl" src="{{ public_path('storage/images/banner.jpg') }}" width="30%" alt="">
        </header>

        <div class="title">
            <h2 class="t1">LAMPIRAN MAUPUN DETAIL TINDAK LANJUT</h2>
        </div>

        <div class="content">
            @if (count($data->getAutorisasiLaporan('TINDAK_LANJUT')) > 0)
                @foreach ($data->getAutorisasiLaporan('TINDAK_LANJUT') as $item)
                    <div class="list-tindak-lanjut">
                        <p>
                            <span>{{ $item->judul }}</span> <br>
                            <small style="text-transform: capitalize;">{{ dateReformat($item->tanggal, 1) }}</small> <br>
                            <p>
                                {{ $item->deskripsi }}
                            </p>

                            <div class="lampiran">
                                @if ($item->lampiran)
                                    <img class="ctl" src="{{ public_path('storage/' . $item->lampiran) }}" width="100%" alt="">
                                @endif
                                
                            </div>
                        </p>
                    </div>
                @endforeach
            @else
                <ul>
                    <li style="text-align:center;">Tidak Ada Laporan Tindakan</li>
                </ul>
            @endif

        </div>
    </section>


</body>

</html>
