<?php

namespace App\Enum;

enum KlasifikasiKode: string
{
    case PENGADUAN = 'PGD';
    case KONSULTASI_HUKUM = 'KTH';
    case KRITIK_DAN_SARAN = 'KDS';
    case PERMOHONAN_DATA = 'PMD';
    case PENGADUAN_LAYANAN = 'PGL';
}
