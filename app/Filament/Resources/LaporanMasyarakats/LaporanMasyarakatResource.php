<?php

namespace App\Filament\Resources\LaporanMasyarakats;

use App\Filament\Resources\LaporanMasyarakats\Pages\CreateLaporanMasyarakat;
use App\Filament\Resources\LaporanMasyarakats\Pages\EditLaporanMasyarakat;
use App\Filament\Resources\LaporanMasyarakats\Pages\ListLaporanMasyarakats;
use App\Filament\Resources\LaporanMasyarakats\Pages\ViewLaporanMasyarakat;
use App\Filament\Resources\LaporanMasyarakats\Schemas\LaporanMasyarakatForm;
use App\Filament\Resources\LaporanMasyarakats\Schemas\LaporanMasyarakatInfolist;
use App\Filament\Resources\LaporanMasyarakats\Tables\LaporanMasyarakatsTable;
use App\Models\LaporanMasyarakat;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaporanMasyarakatResource extends Resource
{
    protected static ?string $model = LaporanMasyarakat::class;

    protected static string|BackedEnum|null $navigationIcon = 'tabler-file-type-doc';
    protected static string | UnitEnum | null $navigationGroup = 'Laporan';

    public static function form(Schema $schema): Schema
    {
        return LaporanMasyarakatForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LaporanMasyarakatInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaporanMasyarakatsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLaporanMasyarakats::route('/'),
            'create' => CreateLaporanMasyarakat::route('/create'),
            'view' => ViewLaporanMasyarakat::route('/{record}'),
            'edit' => EditLaporanMasyarakat::route('/{record}/edit'),
        ];
    }
}
