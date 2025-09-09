<?php

namespace App\Filament\Resources\LaporanMasyarakats\Schemas;

use App\Enum\BanjarEnum;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use App\Enum\KlasifikasiLaporan;
use App\Models\Ungasan\Penduduk;
use Illuminate\Support\Facades\DB;
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
use Filament\Schemas\Components\Utilities\Set;

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
                            Select::make('klasifikasi')
                                ->required()
                                ->columnSpanFull()
                                ->live()
                                ->options(KlasifikasiLaporan::class),
                            Select::make('banjar_kejadian')
                                ->visible(
                                    function ($get): bool {

                                        $klasifikasi = $get('klasifikasi');

                                        switch ($klasifikasi) {
                                            case KlasifikasiLaporan::PERMOHONAN_DATA:
                                                return false;
                                                break;

                                            case KlasifikasiLaporan::PENGADUAN_LAYANAN:
                                                return false;
                                                break;

                                            case KlasifikasiLaporan::KRITIK_DAN_SARAN:
                                                return false;
                                                break;

                                            case KlasifikasiLaporan::KONSULTASI_HUKUM:
                                                return false;
                                                break;
                                        }

                                        return true;
                                    }
                                )
                                ->prefix('Br. ')
                                ->required()
                                ->columnSpanFull()
                                ->options(BanjarEnum::class),
                            // Toggle::make('anonim'),
                            FileUpload::make('lampiran')
                                ->multiple()
                                ->image()
                                ->maxFiles(4)
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
                                ->live()
                                ->afterStateUpdated(
                                    function (Set $set, $state) {
                                        $data = Penduduk::where('nik', $state)->first();

                                        if ($data) {
                                            $kelamin = $data->gender_sid == 1 ? 'laki-laki' : 'perempuan';
                                            $pekerjaan = DB::connection('ungasan')->table('jobs')->where('sid', $data->job_sid)->first();

                                            $set('nama', $data->nama_lengkap);
                                            $set('alamat', $data->alamat);
                                            $set('tanggal_lahir', $data->tanggal_lahir);
                                            $set('jenis_kelamin', $kelamin);
                                            $set('pekerjaan', $pekerjaan->job_name);
                                        }
                                    }
                                )
                                ->required(),
                            TextInput::make('nama')
                                ->required(),
                            Textarea::make('alamat')
                                ->required()
                                ->columnSpanFull(),
                            DatePicker::make('tanggal_lahir')
                                ->required(),
                            Select::make('jenis_kelamin')
                                ->options([
                                    'perempuan' => 'Perempuan',
                                    'laki-laki' => 'Laki Laki',
                                ])
                                ->required(),
                            TextInput::make('no_telpon')
                                ->tel()
                                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                                ->label('No. WhatsApp')
                                ->prefix("+62")
                                ->required(),
                            TextInput::make('pekerjaan')
                                ->required(),
                            FileUpload::make('foto_identitas')
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->visibility('public')
                                ->directory('foto_identitas')
                                ->columnSpanFull()
                        ])
                        ->columns(2),
                ])->columnSpanFull()
            ]);
    }
}
