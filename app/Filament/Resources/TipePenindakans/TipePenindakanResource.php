<?php

namespace App\Filament\Resources\TipePenindakans;

use App\Filament\Resources\TipePenindakans\Pages\ManageTipePenindakans;
use App\Models\TipePenindakan;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class TipePenindakanResource extends Resource
{
    protected static ?string $model = TipePenindakan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama';
    protected static string | UnitEnum | null $navigationGroup = 'Masterdata';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Toggle::make('butuh_anggota')
                    ->label('Butuh Anggota?')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('butuh_anggota')
                    ->label('Tindakan Membutuhkan Anggota Turun Ke Lapangan')
                    ->formatStateUsing(fn ($state) => $state ? 'Dibutuhkan' : 'Tidak Dibutuhkan')
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTipePenindakans::route('/'),
        ];
    }
}
