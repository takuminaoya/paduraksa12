<?php

namespace App;

use App\Enum\KlasifikasiKode;
use App\Enum\KlasifikasiLaporan;
use App\Models\LaporanMasyarakat;

class GenerateTiket
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function generate()
    {
        $datas = LaporanMasyarakat::all();
        foreach ($datas as $data) {

            // update
            if($data->klasifikasi == "POSYANKUMHAMDES"){
                $data->klasifikasi = KlasifikasiLaporan::KONSULTASI_HUKUM;
                $data->save();
            }

            // generate kode
            $kode = "";
            foreach (KlasifikasiKode::cases() as $c) {
                if ($c->name == $data->klasifikasi) {
                    $kode = $c->value;
                }
            }

            $gen = $kode . "-" . date('dmy', strtotime($data->created_at)) . $data->id . "-UNGASAN";
            $data->tiket = $gen;
            $data->save();
        }
    }
}
