<?php

namespace App\Filament\Resources\LaporanMasyarakats\Pages;

use App\Filament\Resources\LaporanMasyarakats\LaporanMasyarakatResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLaporanMasyarakat extends ViewRecord
{
    protected static string $resource = LaporanMasyarakatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
