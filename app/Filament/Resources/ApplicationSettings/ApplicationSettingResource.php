<?php

namespace App\Filament\Resources\ApplicationSettings;

use UnitEnum;
use BackedEnum;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use App\Models\ApplicationSetting;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\ApplicationSettings\Pages\ManageApplicationSettings;
use App\Models\WhatsappTemplate;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;

class ApplicationSettingResource extends Resource
{
    protected static ?string $model = ApplicationSetting::class;

    protected static string|BackedEnum|null $navigationIcon = 'tabler-settings';
    protected static string | UnitEnum | null $navigationGroup = 'Extensi & Peralatan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('key', Str::slug($state))),
                TextInput::make('key')
                    ->readOnly(),
                Textarea::make('deskripsi')
                    ->columnSpanFull()
                    ->nullable(),
                Select::make('value')
                    ->options(WhatsappTemplate::all()->pluck('judul', 'id'))
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama'),
                TextColumn::make('deskripsi')
                    ->wrap(),
                TextColumn::make('key'),
                TextColumn::make('value'),
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
            'index' => ManageApplicationSettings::route('/'),
        ];
    }
}
