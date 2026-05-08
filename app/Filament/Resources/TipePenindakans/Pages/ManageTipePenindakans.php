<?php

namespace App\Filament\Resources\TipePenindakans\Pages;

use App\Filament\Resources\TipePenindakans\TipePenindakanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTipePenindakans extends ManageRecords
{
    protected static string $resource = TipePenindakanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
