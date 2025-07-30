<?php

namespace App\Filament\Resources\LaporanMasyarakats\Tables;

use App\DummyLaporanGenerator;
use App\Enum\KlasifikasiLaporan;
use App\Enum\TipeAutorisasi;
use App\GenerateTiket;
use App\Modules\Whapify;
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
use Illuminate\Support\Facades\Auth;

class LaporanMasyarakatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tiket')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('klasifikasi')
                    ->searchable(),
                TextColumn::make('judul')
                    ->searchable(),
                TextColumn::make('tanggal_kejadian')
                    ->date()
                    ->sortable(),
                TextColumn::make('lokasi_kejadian')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('banjar_kejadian')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                ToggleColumn::make('anonim'),
                ToggleColumn::make('rahasia')
                    ->searchable(),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('klasifikasi')
                    ->options(KlasifikasiLaporan::class),
                SelectFilter::make('status')
                    ->options(TipeAutorisasi::class)
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
                    ),
                Action::make('generate_tiket')
                    ->requiresConfirmation()
                    ->icon('tabler-windmill')
                    ->color(Color::Blue)
                    ->action(
                        function (): void {
                            GenerateTiket::generate();

                            Notification::make()
                                ->title('Tiket telah berhasil digenerasi.')
                                ->success()
                                ->send();
                        }
                    ),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->icon('tabler-edit'),
                    EditAction::make()
                        ->color(Color::Orange)
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
