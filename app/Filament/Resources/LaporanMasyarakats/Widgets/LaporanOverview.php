<?php

namespace App\Filament\Resources\LaporanMasyarakats\Widgets;

use App\Enum\TipeAutorisasi;
use App\Models\LaporanMasyarakat;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LaporanOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $stats = [];
        foreach (TipeAutorisasi::cases() as $k) {
            $count = LaporanMasyarakat::where('status', $k->name)->count();
            $stats[] = Stat::make('TOTAL ' . str_replace("_", " ", $k->name), $count . " Laporan");
        }

        return $stats;
    }
}
