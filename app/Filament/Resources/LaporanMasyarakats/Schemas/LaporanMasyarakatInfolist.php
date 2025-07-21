<?php

namespace App\Filament\Resources\LaporanMasyarakats\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LaporanMasyarakatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Laporan')
                    ->description('Rincian isi laporan yang disampaikan oleh warga kepada pihak desa terkait berbagai permasalahan, keluhan, aspirasi, atau permintaan layanan.')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        TextEntry::make('uuid')
                            ->copyable()
                            ->icon('tabler-copy')
                            ->label('UUID'),
                        TextEntry::make('klasifikasi'),
                        TextEntry::make('judul'),
                        TextEntry::make('tanggal_kejadian')
                            ->date(),
                        TextEntry::make('lokasi_kejadian'),
                        TextEntry::make('banjar_kejadian'),
                        TextEntry::make('anonim')
                            ->badge()
                            ->formatStateUsing(fn($record): string => $record->anonim ? 'Dirahasiakan' : 'Publik'),
                        TextEntry::make('rahasia')
                            ->badge()
                            ->formatStateUsing(fn($record): string => $record->rahasia ? 'Dirahasiakan' : 'Publik'),
                        ImageEntry::make('lampiran')
                            ->disk('public'),
                    ]),

                Section::make('Informasi Pelapor')
                    ->description('Data identitas yang berkaitan dengan warga yang menyampaikan laporan, pengaduan, atau aspirasi kepada pihak desa.')
                    ->columns(2)
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextEntry::make('nik'),
                        TextEntry::make('nama'),
                        TextEntry::make('alamat'),
                        TextEntry::make('tanggal_lahir'),
                        TextEntry::make('jenis_kelamin'),
                        TextEntry::make('no_telpon'),
                        TextEntry::make('pekerjaan'),
                        TextEntry::make('penyandang_disabilitas')
                            ->badge()
                            ->formatStateUsing(fn($record): string => $record->penyandang_disabilitas ? 'Iya' : 'Tidak'),
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ])
            ]);
    }
}
