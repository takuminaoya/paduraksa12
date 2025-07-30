<?php

namespace App\Filament\Resources\LaporanMasyarakats\RelationManagers;

use BackedEnum;
use Filament\Tables\Table;
use App\Enum\TipeAutorisasi;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Actions\DissociateBulkAction;
use Filament\Resources\RelationManagers\RelationManager;

class AutorisasisRelationManager extends RelationManager
{
    protected static string $relationship = 'autorisasis';
    protected static ?string $title = 'Daftar Autorisasi Dokumen';
    protected static string | BackedEnum | null $icon = 'tabler-user';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->description('sebuah dokumen atau sistem yang memuat informasi mengenai individu, jabatan, atau sistem yang memiliki hak atau wewenang tertentu untuk melakukan suatu tindakan atau mengakses data dalam sebuah sistem.')
            ->recordTitleAttribute('user.name')
            ->columns([
                TextColumn::make('tanggal_autorisasi')
                    ->date()
                    ->since()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Nama Autoritas')
                    ->searchable(),
                TextColumn::make('laporan.judul')
                    ->searchable(),
                TextColumn::make('tipe_autorisasi')
                    ->description(
                        function ($record): string {
                            $res = $record->deskripsi;
                            if ($record->tipe_autorisasi == "BATAL") {
                                $res = 'Alasan : ' . $record->deskripsi;
                            }

                            return $res ? $res : "-";
                        }
                    )
                    ->badge()
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->recordActions([
                ActionGroup::make([
                    DeleteAction::make()
                        ->hidden(fn(): bool => !user()->hasRole('super_admin')),
                    Action::make('lihat_lampiran')
                        ->label('Lihat Lampiran')
                        ->icon('tabler-eye')
                        ->visible(fn($record): bool => $record->lampiran ? true : false)
                        ->url(fn($record): string => asset('storage/' . $record->lampiran))
                        ->openUrlInNewTab(),
                    Action::make('download_lampiran')
                        ->label('Download Lampiran')
                        ->icon('tabler-download')
                        ->visible(fn($record): bool => $record->lampiran ? true : false)
                        ->action(
                            function ($record) {
                                return Storage::disk('public')->download($record->lampiran);
                            }
                        )
                        ->openUrlInNewTab()
                ])->dropdownPlacement('top-end')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->hidden(fn(): bool => !user()->hasRole('super_admin')),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
