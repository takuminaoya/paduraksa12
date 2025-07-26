<?php

namespace App\Filament\Resources\LaporanMasyarakats\Pages;

use App\Filament\Resources\LaporanMasyarakats\LaporanMasyarakatResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLaporanMasyarakat extends EditRecord
{
    protected static string $resource = LaporanMasyarakatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
