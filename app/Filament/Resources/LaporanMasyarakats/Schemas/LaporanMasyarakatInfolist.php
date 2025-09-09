<?php

namespace App\Filament\Resources\LaporanMasyarakats\Schemas;

use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

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
                    ->afterHeader([
                        Action::make('status')
                            ->color(
                                function ($record) {
                                    switch ($record->status) {
                                        case 'AKTIF':
                                            return Color::Blue;
                                            break;

                                        case 'PROSES':
                                            return Color::Purple;
                                            break;

                                        case 'VERIFIKASI':
                                            return Color::Orange;
                                            break;

                                        case 'TINDAK_LANJUT':
                                            return Color::Gray;
                                            break;

                                        case 'BATAL':
                                            return Color::Red;
                                            break;

                                        case 'SELESAI':
                                            return Color::Green;
                                            break;
                                    }
                                }
                            )
                            ->label(
                                function ($record) {
                                    if (is_string($record->status)) {
                                        return str_replace('_', ' ', $record->status);
                                    } else {
                                        return $record->status->name;
                                    }
                                }
                            ),
                    ])
                    ->schema([
                        TextEntry::make('tiket')
                            ->copyable()
                            ->icon('tabler-copy'),
                        TextEntry::make('klasifikasi')
                            ->formatStateUsing(fn($state): string => str_replace('_', ' ', $state)),
                        TextEntry::make('judul'),
                        TextEntry::make('isi')
                            ->html()
                            ->columnSpanFull(),
                        TextEntry::make('tanggal_kejadian')
                            ->date('D, d F Y'),
                        TextEntry::make('lokasi_kejadian'),
                        TextEntry::make('banjar_kejadian')
                            ->visible(
                                function ($record): bool {
                                    switch ($record->klasifikasi) {
                                        case "PERMOHONAN_DATA":
                                            return false;
                                            break;

                                        case "PENGADUAN_LAYANAN":
                                            return false;
                                            break;

                                        case "KRITIK_DAN_SARAN":
                                            return false;
                                            break;

                                        case "KONSULTASI_HUKUM":
                                            return false;
                                            break;
                                    }

                                    return true;
                                }
                            ),
                        TextEntry::make('status')
                            ->badge(),
                        ImageEntry::make('lampiran')
                            ->columnSpanFull()
                            ->disk('public'),
                        Action::make('Galeri Gambar Lampiran')
                            ->label("Galeri Gambar Lampiran")
                            ->slideOver()
                            ->icon('tabler-files')
                            ->modalWidth('full')
                            ->modalSubmitAction(false)
                            ->schema(
                                function ($record) : array {
                                    if($record->lampiran){
                                        $conts = [];

                                        foreach ($record->lampiran as $key => $lp) {
                                            $conts[] = ImageEntry::make('Lampiran : ' . $key)
                                                ->imageWidth("fit")
                                                ->imageHeight("100%")
                                                ->state(asset('storage/' . $lp));
                                        }

                                        return $conts;
                                    }

                                    return [];
                                }
                            )
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
                        TextEntry::make('tanggal_lahir')
                            ->date(),
                        TextEntry::make('jenis_kelamin'),
                        TextEntry::make('no_telpon')
                            ->prefix('+62'),
                        TextEntry::make('pekerjaan'),
                        TextEntry::make('created_at')
                            ->label('Dibuat pada')
                            ->dateTime(),
                        ImageEntry::make('foto_identitas')
                            ->disk('public')
                            ->defaultImageUrl(url('storage/images/placeholder.jpg'))
                    ])
            ]);
    }
}
