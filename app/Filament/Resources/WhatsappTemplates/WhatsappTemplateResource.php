<?php

namespace App\Filament\Resources\WhatsappTemplates;

use App\Filament\Forms\Components\TemplatePicker;
use BackedEnum;
use UnitEnum;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use App\Models\WhatsappTemplate;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\WhatsappTemplates\Pages\ManageWhatsappTemplates;
use App\Models\LaporanMasyarakat;
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;

class WhatsappTemplateResource extends Resource
{
    protected static ?string $model = WhatsappTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = 'tabler-brand-whatsapp';
    protected static string | UnitEnum | null $navigationGroup = 'Extensi & Peralatan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn($set, ?string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->readOnly()
                    ->required(),
                Action::make('daftar_template')
                    ->icon('tabler-plus')
                    ->slideOver()
                    ->schema([
                        TextEntry::make('Pemberitahuan')
                            ->state('Klik salah satu Tag untuk mengkopi tag yang dapat digunakan pada penulisan template baru.'),
                        TextEntry::make('Daftar Laporan Masyarakat Tag')
                            ->label('Daftar Laporan Masyarakat Tag')
                            ->state(
                                function () : array {
                                    $tags = LaporanMasyarakat::getListOfTagsOnly();
                                    $results = [];

                                    foreach($tags as $tag){
                                        $results[] = "*" . $tag . "*";
                                    }

                                    return $results;
                                }
                            )
                            ->icon('tabler-copy')
                            ->copyable()
                            ->badge()
                            ->extraAttributes([
                                'hover:bg-ember-300'
                            ])
                    ])->modalSubmitAction(false),
                Textarea::make('isi')
                    ->columnSpanFull()
                    ->rows(15)
                    ->required(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug'),
                TextColumn::make('judul'),
                TextColumn::make('isi')
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
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
            'index' => ManageWhatsappTemplates::route('/'),
        ];
    }
}
