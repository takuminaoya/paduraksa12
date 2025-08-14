<?php

namespace App\Filament\Resources\LaporanMasyarakats;

use UnitEnum;
use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use App\Models\LaporanMasyarakat;
use Filament\Support\Icons\Heroicon;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use App\Filament\Resources\LaporanMasyarakats\Pages\EditLaporanMasyarakat;
use App\Filament\Resources\LaporanMasyarakats\Pages\ViewLaporanMasyarakat;
use App\Filament\Resources\LaporanMasyarakats\Pages\ListLaporanMasyarakats;
use App\Filament\Resources\LaporanMasyarakats\Pages\CreateLaporanMasyarakat;
use App\Filament\Resources\LaporanMasyarakats\RelationManagers\AutorisasisRelationManager;
use App\Filament\Resources\LaporanMasyarakats\RelationManagers\TanggapansRelationManager;
use App\Filament\Resources\LaporanMasyarakats\RelationManagers\WhatsappsRelationManager;
use App\Filament\Resources\LaporanMasyarakats\Schemas\LaporanMasyarakatForm;
use App\Filament\Resources\LaporanMasyarakats\Tables\LaporanMasyarakatsTable;
use App\Filament\Resources\LaporanMasyarakats\Schemas\LaporanMasyarakatInfolist;

class LaporanMasyarakatResource extends Resource implements HasShieldPermissions
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
            AutorisasisRelationManager::class,
            WhatsappsRelationManager::class,
            // TanggapansRelationManager::class
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'verifikasi',
            'tindak_lanjut',
            'selesai',
            'batal',
            'hapus_autoritas'
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
