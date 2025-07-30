<?php

namespace App\Enum;

enum KlasifikasiLaporan: string
{
    case PENGADUAN = 'PENGADUAN';
    case KONSULTASI_HUKUM = 'KONSULTASI_HUKUM';
    case KRITIK_DAN_SARAN = 'KRITIK_DAN_SARAN';
    case PERMOHONAN_DATA = 'PERMOHONAN_DATA';
}
