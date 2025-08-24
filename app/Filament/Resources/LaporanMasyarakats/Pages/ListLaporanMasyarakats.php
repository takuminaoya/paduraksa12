<?php

namespace App\Filament\Resources\LaporanMasyarakats\Pages;

use App\Enum\TipeAutorisasi;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\LaporanMasyarakats\Widgets\LaporanOverview;
use App\Filament\Resources\LaporanMasyarakats\LaporanMasyarakatResource;
use App\Models\LaporanMasyarakat;
use Illuminate\Database\Eloquent\Builder;

class ListLaporanMasyarakats extends ListRecords
{
    protected static string $resource = LaporanMasyarakatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->hidden(fn() => !superAdmin() ? true : false)
                ->icon('tabler-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LaporanOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua')
                ->icon('tabler-checks'),
            'proses' => Tab::make('Diproses')
                ->badgeColor('primary')
                ->icon('tabler-arrows-exchange')
                ->badge(
                    LaporanMasyarakat::where('status', TipeAutorisasi::PROSES)->count()
                )
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', TipeAutorisasi::PROSES)),

            'verifikasi' => Tab::make('Diverifikasi')
                ->badgeColor('info')
                ->icon('tabler-check')
                ->badge(
                    LaporanMasyarakat::where('status', TipeAutorisasi::VERIFIKASI)->count()
                )
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', TipeAutorisasi::VERIFIKASI)),
            'tindak_lanjut' => Tab::make('Ditindak lanjuti')
                ->badgeColor('warning')
                ->icon('tabler-truck')
                ->badge(
                    LaporanMasyarakat::where('status', TipeAutorisasi::TINDAK_LANJUT)->count()
                )
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', TipeAutorisasi::TINDAK_LANJUT)),
            'batal' => Tab::make('Dibatalkan')
                ->badgeColor('danger')
                ->icon('tabler-x')
                ->badge(
                    LaporanMasyarakat::where('status', TipeAutorisasi::BATAL)->count()
                )
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', TipeAutorisasi::BATAL)),
            'selesai' => Tab::make('Diselesaikan')
                ->badgeColor('gray')
                ->icon('tabler-circle-dashed-check')
                ->badge(
                    LaporanMasyarakat::where('status', TipeAutorisasi::SELESAI)->count()
                )
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', TipeAutorisasi::SELESAI)),
        ];
    }
}
