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
        }

        .detail-pelapor {
            margin-left: 2rem;
        }

        .kata-1 {
            text-indent: 2rem;
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
    </style>

    <section class="body page-break">
        <header>
            <img class="ctl" src="{{ public_path('storage/images/banner.jpg') }}" width="30%" alt="">
        </header>

        <div class="content" style="width:100%;">
            <p class="tanggal">
                Ungasan, {{ dateReformat($data->created_at, 1) }}
            </p>

            <p class="kepada">
                Kepada : <br>
                Yth. Perbekel Ungasan <br>
                Di <br>
                Tempat <br>
            </p>

            <div class="detail">
                <table style="width: 100%;">
                    <tr>
                        <td style="width:10%;">Nomor</td>
                        <td style="width:3%;">:</td>
                        <td>{{ $data->tiket }}</td>
                    </tr>
                    <tr>
                        <td style="width:10%;">Perihal</td>
                        <td style="width:3%;">:</td>
                        <td>{{ $data->judul }}</td>
                    </tr>
                </table>
            </div>

            <p class="dengan-hormat">
                Dengan Hormat,
            </p>

            <p class="detail-2">
                Saya yang bertandatangan dibawah ini : <br>
            </p>

            <div class="detail-pelapor">
                <table style="width: 100%;">
                    <tr>
                        <td style="width:20%;">NIK</td>
                        <td style="width:1%;">:</td>
                        <td>{{ strtolower($data->nik) }}</td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Nama</td>
                        <td style="width:1%;">:</td>
                        <td>{{ strtolower($data->nama) }}</td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Alamat</td>
                        <td style="width:1%;">:</td>
                        <td>{{ strtolower($data->alamat) }}</td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Tanggal Lahir</td>
                        <td style="width:1%;">:</td>
                        <td>{{ strtolower(dateReformat($data->tanggal_lahir)) }}</td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Jenis Kelamin</td>
                        <td style="width:1%;">:</td>
                        <td>{{ strtolower($data->jenis_kelamin) }}</td>
                    </tr>
                    <tr>
                        <td style="width:20%;">No Telpon / Whatsapp</td>
                        <td style="width:1%;">:</td>
                        <td>{{ strtolower($data->no_telpon) }}</td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Pekerjaan</td>
                        <td style="width:1%;">:</td>
                        <td>{{ strtolower($data->pekerjaan) }}</td>
                    </tr>
                </table>
            </div>

            <p class="kata-1">
                Melalui Sistem paduraksa ini saya ingin menyampaikan <span>{{ strtolower(str_replace("_", " ", $data->klasifikasi)) }}</span> yaitu <span>{{ strtolower($data->judul) }}</span> pada
                tanggal <span>{{ dateReformat($data->created_at) }}</span> bertempat di <span>{{ strtolower($data->lokasi_kejadian) }}</span> banjar <span>{{ strtolower($data->banjar_kejadian) }}</span> bahwa <span>{{ $data->isi }}</span>
            </p>

            <p class="kata-2">
                Demikian Kategori paduraksa ini saya sampaikan. Dengan ini kami mohon bapak Perbekel Ungasan dapat
                meningdaklanjuti surat ini
            </p>

            <p class="hormat-saya">
                Hormat Saya <br>
                <br>
                <br>
                {{ strtolower($data->nama) }}
            </p>
        </div>
    </section>

    <section>
        <header>
            <img class="ctl" src="{{ public_path('storage/images/banner.jpg') }}" width="30%" alt="">
        </header>

        <div class="title">
            <h2 class="t1">LAMPIRAN FOTO PENDUKUNG</h2>
        </div>

        <div class="content">
            <img class="lampiran" src="{{ public_path('storage/' . $data->lampiran) }}" alt="">
        </div>
    </section>


</body>

</html>
