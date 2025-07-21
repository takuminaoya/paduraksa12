<?php

namespace App\Filament\Resources\LaporanMasyarakats\Tables;

use App\DummyLaporanGenerator;
use App\Enum\KlasifikasiLaporan;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Support\Colors\Color;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;

class LaporanMasyarakatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('klasifikasi')
                    ->searchable(),
                TextColumn::make('judul')
                    ->searchable(),
                TextColumn::make('tanggal_kejadian')
                    ->date()
                    ->sortable(),
                TextColumn::make('lokasi_kejadian')
                    ->searchable(),
                TextColumn::make('banjar_kejadian')
                    ->searchable(),
                ToggleColumn::make('anonim'),
                ToggleColumn::make('rahasia')
                    ->searchable(),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('klasifikasi')
                    ->options(KlasifikasiLaporan::class)
            ])
            ->headerActions([
                Action::make('Generate Dummy Data')
                    ->requiresConfirmation()
                    ->icon('tabler-windmill')
                    ->color(Color::Blue)
                    ->action(
                        function (): void {
                            $gen = new DummyLaporanGenerator(5);
                            $gen->generate();
                        }
                    )
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->icon('tabler-edit'),
                    EditAction::make()
                        ->icon('tabler-x'),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
