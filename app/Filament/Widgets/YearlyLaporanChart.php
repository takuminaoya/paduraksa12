<?php

namespace App\Filament\Widgets;

use App\Enum\TipeAutorisasi;
use App\Models\LaporanMasyarakat;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class YearlyLaporanChart extends ChartWidget
{
    protected ?string $heading = 'Laporan Masyarakat Pertahun Berdasarkan Tingkat Status';
    protected int | string | array $columnSpan = 'full';
    public ?string $filter = '2025';

    protected function getFilters(): ?array
    {

        $years = [];

        for ($i = 2025; $i <= date('Y'); $i++) {
            $years[] = $i;
        }

        return $years;
    }

    protected function getData(): array
    {

        $datasets = [];
        $colors = [];

        foreach (TipeAutorisasi::cases() as $item) {

            switch ($item->name) {
                case 'AKTIF':
                    $colors[$item->name] = '#006992';
                    break;

                case 'PROSES':
                    $colors[$item->name] = '#ECA400 ';
                    break;

                case 'VERIFIKASI':
                    $colors[$item->name] = '#EAF8BF';
                    break;

                case 'TINDAK_LANJUT':
                    $colors[$item->name] = '#E6E8E6';
                    break;

                case 'BATAL':
                    $colors[$item->name] = '#DF2935';
                    break;

                case 'SELESAI':
                    $colors[$item->name] = '#A2D729';
                    break;
            }

            $datas = [];
            for ($i = 1; $i <= 12; $i++) {
                $datas[] = LaporanMasyarakat::where('status', $item->name)
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', $this->filter)
                    ->count();
            }

            $datasets[] = [
                'label' => 'Jumlah yang : ' . $item->name,
                'data' => $datas,
                'backgroundColor' => $colors[$item->name],
                'borderColor' => '#CCC',
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
