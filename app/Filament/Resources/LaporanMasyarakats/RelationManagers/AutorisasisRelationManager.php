<?php

namespace App\Filament\Resources\LaporanMasyarakats\RelationManagers;

use App\Enum\TipeAutorisasi;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AutorisasisRelationManager extends RelationManager
{
    protected static string $relationship = 'autorisasis';
    protected static ?string $title = 'Daftar Autorisasi Dokumen';

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
                DeleteAction::make()
                    ->hidden(fn(): bool => !user()->hasRole('super_admin')),
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
