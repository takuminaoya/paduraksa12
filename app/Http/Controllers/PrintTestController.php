<?php

namespace App\Http\Controllers;

use App\Models\LaporanMasyarakat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class PrintTestController extends Controller
{
    public function preview(){

        Carbon::setLocale('IND');
        App::setLocale('IND');

        $data = LaporanMasyarakat::find(60);

        $pdf = Pdf::loadView('print.tanggapan', [
            'data' => $data
        ]);
        return $pdf->stream();
    }
}
