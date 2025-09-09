<?php

namespace App\Livewire;

use Livewire\Component;
use App\Enum\TipeAutorisasi;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Enum\KlasifikasiLaporan;
use App\Models\LaporanMasyarakat;
use Filament\Support\Colors\Color;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class HalamanUtama extends Component implements HasSchemas
{

    use InteractsWithSchemas;

    public $laporans = [];
    public $tiket;
    public $laporan = null;

    public function mount()
    {
        $cases = [];
        foreach (KlasifikasiLaporan::cases() as $c) {
            $cases[] = [
                "name" => $c->name,
                "total" => LaporanMasyarakat::where('klasifikasi', $c->name)->count()
            ];
        }

        $stats = [];
        foreach (TipeAutorisasi::cases() as $c) {
            $stats[] = [
                "name" => $c->name,
                "total" => LaporanMasyarakat::where('status', $c->name)->count()
            ];
        }

        $this->laporans = [
            "total" => LaporanMasyarakat::all()->count(),
            "klass" => $cases,
            "stats" => $stats
        ];
    }

    public function check()
    {
        $validate = $this->validate([
            'tiket' => 'required'
        ]);

        $check = LaporanMasyarakat::where('tiket', $validate['tiket'])->first();

        if ($check) {
            $this->laporan = $check;
        } else {
            session()->flash('status', 'Tiket anda salah atau tidak terdaftar. mohon lakukan pengecekan dipesan whatsapp yang dikirim saat pendaftaran.');
        }
    }

    public function laporanInfolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->laporan)
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
                        TextEntry::make('klasifikasi'),
                        TextEntry::make('judul'),
                        TextEntry::make('isi')
                            ->html()
                            ->columnSpanFull(),
                        TextEntry::make('tanggal_kejadian')
                            ->date(),
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
                    ]),

                Section::make('Informasi Pelapor')
                    ->description('Data identitas yang berkaitan dengan warga yang menyampaikan laporan, pengaduan, atau aspirasi kepada pihak desa.')
                    ->columns(2)
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextEntry::make('nik')
                            ->limit(5, '***********'),
                        TextEntry::make('nama'),
                        TextEntry::make('alamat'),
                        TextEntry::make('tanggal_lahir'),
                        TextEntry::make('jenis_kelamin'),
                        TextEntry::make('no_telpon')
                            ->limit(5, '***********'),
                        TextEntry::make('pekerjaan'),
                        TextEntry::make('created_at')
                            ->label('Dibuat pada')
                            ->dateTime(),
                    ])
            ]);
    }

    public function render()
    {
        return view('livewire.halaman-utama');
    }
}
