<?php

namespace App\Http\Controllers;

use App\Enum\KlasifikasiLaporan;
use App\Enum\TipeAutorisasi;
use App\Models\LaporanMasyarakat;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HalamanPublikSosmed extends Controller
{
    public function index() {
        $results = [];
        $lps = LaporanMasyarakat::where('status', 'SELESAI')->latest()->get();

        foreach($lps as $l){
            $lampirans = [];

            if(count($l->lampiran) > 0){
                foreach($l->lampiran as $lp){
                    $lampirans[] = [
                        "time" => dateReformat(date('Y-m-d'), 1),
                        "text" => '',
                        "img" => null,
                        "atts" => [
                            asset('storage/' . $lp)
                        ]
                    ];

                }
            }

            // tambhakan lampiran tindak lanjut
            $tindak_lanjut = $l->oautorisasi(TipeAutorisasi::TINDAK_LANJUT);
            if($tindak_lanjut){
                $lampirans[] = [
                    "time" => dateReformat($tindak_lanjut->tanggal_autorisasi, 1),
                    "text" => $tindak_lanjut->deskripsi,
                    "img" => null,
                    "atts" => []

                ];
            }

            $selesai = $l->oautorisasi(TipeAutorisasi::SELESAI);
            if($selesai){
                $attse = [];

                if($selesai->lampiran){
                    foreach($selesai->lampiran as $al){
                        $attse[] = asset('storage/' . $al);
                    }
                }

                $lampirans[] = [
                    "time" => dateReformat($selesai->tanggal_autorisasi, 1),
                    "text" => $selesai->deskripsi,
                    "img" => null,
                    "atts" => $attse
                ];
            }

            $results[] = [
                "id" => $l->tiket,
                "title" => $l->judul,
                "description" => $l->isi,
                "category" => $l->klasifikasi,
                "status" => $l->status,
                "priority" => "Medium",
                "resident" => "Anonim",
                "unit" => $l->banjar_kejadian,
                "location" => $l->alamat,
                "date" => $l->tanggal_kejadian,
                "updated" => $l->updated_at,
                "assignee" => "Aparatur Desa Ungasan",
                "timeline" => $lampirans
            ];
        }


        return view('halaman_publik_sosmed', [
            'datas' => json_encode($results),
            'klass' => KlasifikasiLaporan::cases(),
            'statuses' => TipeAutorisasi::cases()
        ]);
    }
}
