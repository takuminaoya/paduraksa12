<?php

namespace App\Filament\Resources\LaporanMasyarakats\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\ImageColumn;

class TanggapansRelationManager extends RelationManager
{
    protected static string $relationship = 'tanggapans';
    protected static ?string $title = 'Tanggapan Masyarakat';
    protected static string | BackedEnum | null $icon = 'tabler-message';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('tanggal')
                    ->required()
                    ->default(fn(): string => Carbon::now()),
                MarkdownEditor::make('deskripsi')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                FileUpload::make('lampiran')
                    ->disk('public')
                    ->directory('tanggapan')
                    ->visibility('public')
                    ->image()
                    ->columnSpanFull()
                    ->imageEditor()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('deskripsi')
            ->columns([
                TextColumn::make('tanggal')
                    ->date('D, d F Y')
                    ->searchable(),
                TextColumn::make('deskripsi')
                    ->searchable(),
                ImageColumn::make('lampiran')
                    ->disk('public')
                    ->circular()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('tanggapi')
                        ->schema([
                            TextInput::make('judul')
                                ->required(),
                            DatePicker::make('tanggal')
                                ->required()
                                ->default(fn(): string => Carbon::now()),
                            MarkdownEditor::make('deskripsi')
                                ->required()
                                ->columnSpanFull()
                                ->maxLength(255),
                            FileUpload::make('lampiran')
                                ->disk('public')
                                ->directory('auth_lampiran')
                                ->visibility('public')
                                ->nullable()
                                ->image()
                                ->columnSpanFull()
                                ->imageEditor()
                        ])
                        ->action(
                            function ($record, $data, $livewire) {
                                dd($livewire->ownerRecord);
                            }
                        )
                        ->color(Color::Blue)
                        ->icon('tabler-message'),
                    EditAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
