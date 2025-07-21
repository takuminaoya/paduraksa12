<?php

namespace App\Filament\Resources\LaporanMasyarakats\Pages;

use App\Filament\Resources\LaporanMasyarakats\LaporanMasyarakatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLaporanMasyarakats extends ListRecords
{
    protected static string $resource = LaporanMasyarakatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('tabler-plus'),
        ];
    }
}
