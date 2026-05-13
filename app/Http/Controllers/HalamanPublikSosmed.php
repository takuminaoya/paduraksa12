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
        $lps = LaporanMasyarakat::limit(12)->latest()->get();

        foreach($lps as $l){
            $lampirans = [];

            if(count($l->lampiran) > 0){
                foreach($l->lampiran as $lp){
                    $lampirans[] = [
                        "time" => dateReformat(date('Y-m-d'), 1),
                        "text" => asset('storage/' . $lp)
                    ];
                }
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
