<?php

namespace App\Filament\Resources\LaporanMasyarakats\Schemas;

use App\Enum\BanjarEnum;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use App\Enum\KlasifikasiLaporan;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Components\MarkdownEditor;

class LaporanMasyarakatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('uuid')
                    ->default(fn(): string => Str::uuid()),
                Wizard::make([
                    Step::make('Data Laporan')
                        ->description('Informasi Laporan Anda')
                        ->icon('tabler-file-type-doc')
                        ->schema([
                            TextInput::make('judul')
                                ->required()
                                ->columnSpanFull(),
                            MarkdownEditor::make('isi')
                                ->required()
                                ->fileAttachmentsDisk('public')
                                ->fileAttachmentsVisibility('public')
                                ->fileAttachmentsDirectory('attachments')
                                ->columnSpanFull(),
                            DatePicker::make('tanggal_kejadian')
                                ->default(fn() => Carbon::now())
                                ->required(),
                            TextInput::make('lokasi_kejadian')
                                ->required()
                                ->columnSpanFull(),
                            Select::make('banjar_kejadian')
                                ->prefix('Br. ')
                                ->required()
                                ->columnSpanFull()
                                ->options(BanjarEnum::class),
                            Select::make('klasifikasi')
                                ->required()
                                ->columnSpanFull()
                                ->options(KlasifikasiLaporan::class),
                            Toggle::make('anonim'),
                            Toggle::make('rahasia'),
                            FileUpload::make('lampiran')
                                ->imageEditor()
                                ->disk('public')
                                ->visibility('public')
                                ->directory('lampiran')
                                ->columnSpanFull()
                                ->nullable()

                        ])
                        ->columns(2),
                    Step::make('Data Pelapor')
                        ->description('Informasi Diri Anda')
                        ->icon('tabler-user')
                        ->schema([
                            TextInput::make('nik')
                                ->minLength(16)
                                ->maxLength(16)
                                ->required(),
                            TextInput::make('nama')
                                ->required(),
                            TextInput::make('judul')
                                ->required()
                                ->columnSpanFull(),
                            Textarea::make('alamat')
                                ->required()
                                ->columnSpanFull(),
                            DatePicker::make('tanggal_lahir')
                                ->required(),
                            Select::make('jenis_kelamin')
                                ->options([
                                    'rahasia' => 'Memilih Tidak Menyebutkan',
                                    'perempuan' => 'Perempuan',
                                    'laki-laki' => 'Laki Laki',
                                ])
                                ->required(),
                            TextInput::make('no_telpon')
                                ->prefix("+62")
                                ->required(),
                            TextInput::make('pekerjaan')
                                ->required(),
                            Toggle::make('penyandang_disabilitas'),
                        ])
                        ->columns(2),
                ])->columnSpanFull()
            ]);
    }
}
