<?php

namespace App\Http\Controllers;

use App\Models\LaporanMasyarakat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class PrintTestController extends Controller
{
    public function preview($id)
    {

        Carbon::setLocale('IND');
        App::setLocale('IND');

        $data = LaporanMasyarakat::find($id);

        $pdf = Pdf::loadView('print.test', [
            'data' => $data
        ]);
        return $pdf->setPaper('f4')->stream();
    }

    public function tanggapan($id)
    {

        Carbon::setLocale('IND');
        App::setLocale('IND');

        $data = LaporanMasyarakat::find($id);

        $pdf = Pdf::loadView('print.tanggapan', [
            'data' => $data
        ]);
        return $pdf->setPaper('f4')->stream();
    }
}
