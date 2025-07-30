<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Schemas\Schema;
use App\Enum\KlasifikasiLaporan;
use App\Models\LaporanMasyarakat;
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

        $this->laporans = [
            "total" => LaporanMasyarakat::all()->count(),
            "klass" => $cases
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
                    ->schema([
                        TextEntry::make('tiket')
                            ->copyable()
                            ->icon('tabler-copy')
                            ->label('UUID'),
                        TextEntry::make('klasifikasi'),
                        TextEntry::make('judul'),
                        TextEntry::make('isi')
                            ->html()
                            ->columnSpanFull(),
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

    public function render()
    {
        return view('livewire.halaman-utama');
    }
}
