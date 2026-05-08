<?php

namespace App\Filament\Resources\LaporanMasyarakats\Pages;

use App\Enum\TipeAutorisasi;
use App\Filament\Resources\LaporanMasyarakats\LaporanMasyarakatResource;
use App\Models\Absen\AbsenLembaga;
use App\Models\Absen\AbsenUser;
use App\Models\AnggotaPenindakan;
use App\Models\LaporanAutorisasi;
use App\Models\TipePenindakan;
use AshAllenDesign\ShortURL\Classes\Builder;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ViewLaporanMasyarakat extends ViewRecord
{
    protected static string $resource = LaporanMasyarakatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('Reset Laporan')
                    ->icon('tabler-restore')
                    ->hidden(fn(): bool => !user()->hasRole('super_admin'))
                    ->requiresConfirmation()
                    ->action(function ($record): void {
                        $record->status = 'AKTIF';
                        $record->save();

                        $auth_lapors = $record->autorisasis;
                        $aggs = $record->anggotas;

                        foreach ($auth_lapors as $al) {
                            $al->delete();
                        }

                        foreach ($aggs as $ag) {
                            $ag->delete();
                        }

                        Notification::make()
                            ->title('Laporan telah direset menjadi aktif dan semua autoritas yang ada telah dihapus.')
                            ->success()
                            ->send();
                    }),
                EditAction::make()
                    ->icon('tabler-edit'),
                Action::make('print')
                    ->hidden(fn($livewire): bool => $livewire->record->autorisasi(TipeAutorisasi::PROSES))
                    ->requiresConfirmation()
                    ->icon('tabler-printer') 
                    ->action(
                        function ($record, $livewire) {

                            $lps = [
                                $livewire->record->tiket . '.pdf'
                            ];

                            $user = Auth::user();
                            LaporanAutorisasi::create([
                                'user_id' => $user->id,
                                'laporan_masyarakat_id' => $record->id,
                                'tipe_autorisasi' => TipeAutorisasi::PROSES,
                                'tanggal_autorisasi' => Carbon::now(),
                                'lampiran' => $lps,
                            ]);

                            $record->status = TipeAutorisasi::PROSES;
                            $record->save();

                            $pdf = Pdf::loadView('print.test', [
                                "data" => $livewire->record
                            ])->save($livewire->record->tiket . '.pdf', 'public');

                            $path = $livewire->record->tiket . '.pdf';

                            Notification::make()
                                ->title('Print telah selesai.')
                                ->actions([
                                    Action::make('lihat')
                                        ->url(asset('storage/' . $record->tiket . '.pdf'))
                                        ->openUrlInNewTab()
                                        ->button()
                                        ->markAsUnread(),
                                ])
                                ->success()
                                ->sendToDatabase(superAdmin())
                                ->send();

                            autoSendWhatsapp($record->id, 'proses');

                            return Storage::disk('public')->download($path);
                        }
                    ),
                Action::make('print_tanggapan')
                    ->label('Print Tanggapan')
                    ->visible(fn($livewire): bool => $livewire->record->autorisasi(TipeAutorisasi::SELESAI))
                    ->requiresConfirmation()
                    ->icon('tabler-printer')
                    ->color(Color::Blue)
                    ->action(
                        function ($record, $livewire) {
                            $pdf = Pdf::loadView('print.tanggapan', [
                                "data" => $livewire->record
                            ])->save($livewire->record->tiket . '_tanggapan.pdf', 'public');

                            $path = $livewire->record->tiket . '_tanggapan.pdf';

                            Notification::make()
                                ->title('Print tanggapan telah selesai.')
                                ->actions([
                                    Action::make('lihat')
                                        ->url(asset('storage/' . $record->tiket . '_tanggapan.pdf'))
                                        ->openUrlInNewTab()
                                        ->button()
                                        ->markAsUnread(),
                                ])
                                ->success()
                                ->sendToDatabase(superAdmin())
                                ->send();

                            autoSendWhatsapp($record->id, 'proses');

                            return Storage::disk('public')->download($path);
                        }
                    ),

                // muncul setelah proses
                ActionGroup::make([
                    Action::make('verifikasi_laporan')
                        ->color(Color::Blue)
                        ->icon('tabler-signature')
                        ->authorize('verifikasi_laporan::masyarakats::laporan::masyarakat')
                        ->disabled(fn($record): bool => $record->autorisasi(TipeAutorisasi::VERIFIKASI))
                        ->requiresConfirmation()
                        ->action(
                            function ($record): void {
                                $user = Auth::user();
                                LaporanAutorisasi::create([
                                    'user_id' => $user->id,
                                    'laporan_masyarakat_id' => $record->id,
                                    'tipe_autorisasi' => TipeAutorisasi::VERIFIKASI,
                                    'tanggal_autorisasi' => Carbon::now()
                                ]);

                                $record->status = TipeAutorisasi::VERIFIKASI;
                                $record->save();

                                Notification::make()
                                    ->title('Laporan telah terverifikasi oleh ' . $user->name)
                                    ->success()
                                    ->actions([
                                        Action::make('view')
                                            ->url($record->id)
                                            ->button()
                                            ->markAsUnread(),
                                    ])
                                    ->sendToDatabase(superAdmin())
                                    ->send()
                                ;

                                autoSendWhatsapp($record->id, 'verifikasi');
                            }
                        ),
                    Action::make('tindak_lanjut_laporan')
                        ->icon('tabler-trekking')
                        ->color(Color::Emerald)
                        ->authorize('tindak_lanjut_laporan::masyarakats::laporan::masyarakat')
                        ->disabled(fn($record): bool => $record->autorisasi(TipeAutorisasi::TINDAK_LANJUT))
                        ->modalWidth(Width::SixExtraLarge)
                        ->schema([
                            Select::make('tipe_penindakan_id')
                                ->searchable()
                                ->live()
                                ->options(TipePenindakan::query()->pluck('nama', 'id'))
                                ->columnSpanFull(),
                            // Jika dibutuhkan anggota
                            Repeater::make('anggotas')
                                ->label('Daftar Anggota')
                                ->visible(
                                    function ($get): bool {
                                        if ($get('tipe_penindakan_id')) {
                                            $tp = TipePenindakan::find($get('tipe_penindakan_id'));
                                            if ($tp->butuh_anggota == true) {
                                                return true;
                                            }
                                        }
                                        return false;
                                    }
                                )
                                ->columns(2)
                                ->schema([
                                    Select::make('tipe')
                                        ->required()
                                        ->live()
                                        ->options([
                                            'lembaga' => 'Lembaga',
                                            'staff' => 'Staff / Pegawai'
                                        ]),
                                    Select::make('lembaga')
                                        ->live()
                                        ->visible(
                                            function ($get): bool {
                                                if ($get('tipe') && $get('tipe') === "lembaga") {

                                                    return true;
                                                }

                                                return false;
                                            }
                                        )
                                        ->searchable()
                                        ->options(
                                            function (): array {
                                                $results = [];

                                                $lembagas = AbsenLembaga::get();
                                                foreach ($lembagas as $l) {
                                                    $results[$l->id] = $l->nama . " - " . $l->nama_orang;
                                                }

                                                return $results;
                                            }
                                        ),
                                    Select::make('staff')
                                        ->live()
                                        ->visible(
                                            function ($get): bool {
                                                if ($get('tipe') && $get('tipe') === "staff") {

                                                    return true;
                                                }

                                                return false;
                                            }
                                        )
                                        ->searchable()
                                        ->options(
                                            function (): array {
                                                $results = [];

                                                $lembagas = AbsenUser::get();
                                                foreach ($lembagas as $l) {
                                                    $results[$l->id] = $l->name;
                                                }

                                                return $results;
                                            }
                                        ),
                                ])
                                ->collapsible()
                                ->itemLabel(
                                    function ($state) {
                                        if ($state['tipe'] == 'lembaga') {
                                            if ($state['lembaga']) {
                                                $al = AbsenLembaga::find($state['lembaga']);
                                                return $al->nama . " - " . $al->nama_orang;
                                            }
                                        }

                                        if ($state['tipe'] == 'staff') {
                                            if ($state['staff']) {
                                                $au = AbsenUser::find($state['staff']);
                                                return $au->name;
                                            }
                                        }

                                        return null;
                                    }
                                ),
                            MarkdownEditor::make('deskripsi')
                                ->required(),
                            TextInput::make('nomor_surat')
                                ->prefixIcon('tabler-number')
                                ->readOnly()
                                ->default(fn ($record) => $record->id)
                                ->required(),
                            FileUpload::make('lampiran')
                                ->multiple()
                                ->maxFiles(4)
                                ->image()
                                ->imageEditor()
                                ->visibility('public')
                                ->directory('autoritas_lampiran')
                                ->disk('public')

                        ])
                        ->hidden(function ($livewire): bool {
                            $record = $livewire->record;

                            if ($record->autorisasi(TipeAutorisasi::VERIFIKASI)) {
                                return false;
                            }

                            return true;
                        })
                        ->action(
                            function ($data, $record): void {

                                $templateSlug = "whatsapp-anggota-penanganan";

                                $user = Auth::user();
                                LaporanAutorisasi::create([
                                    'user_id' => $user->id,
                                    'laporan_masyarakat_id' => $record->id,
                                    'tipe_autorisasi' => TipeAutorisasi::TINDAK_LANJUT,
                                    'tanggal_autorisasi' => Carbon::now(),
                                    'deskripsi' => $data['deskripsi'],
                                    'tipe_penindakan_id' => $data['tipe_penindakan_id'],
                                    'lampiran' => $data['lampiran'],
                                    'nomor_surat' => $data['nomor_surat'],
                                ]);

                                $record->status = TipeAutorisasi::TINDAK_LANJUT;
                                $record->save();

                                // simpan anggota jika tipe membutuhkan 
                                $penindakan = TipePenindakan::find($data['tipe_penindakan_id']);
                                if ($penindakan->butuh_anggota == true) {
                                    foreach ($data['anggotas'] as $ag) {

                                        if ($ag['tipe'] == 'lembaga') {
                                            $dt = AbsenLembaga::find($ag[$ag['tipe']]);
                                            $dts = [
                                                'nama' => $dt->nama_orang,
                                                'jabatan' => $dt->nama
                                            ];

                                            autoSendWhatsapp($record->id, null, $dt->no_telp, 'whatsapp-anggota-penanganan');
                                        }

                                        if ($ag['tipe'] == 'staff') {
                                            $dt = AbsenUser::find($ag[$ag['tipe']]);
                                            $j = DB::connection('absen')->table('pengguna_jabatans')->where('id', $dt->jabatan_id)->first();
                                            $dts = [
                                                'nama' => $dt->name,
                                                'jabatan' => $j->nama
                                            ];

                                            autoSendWhatsapp($record->id, null, $dt->phone, 'whatsapp-anggota-penanganan');

                                        }

                                        $ap = new AnggotaPenindakan();
                                        $ap->laporan_masyarakat_id = $record->id;
                                        $ap->tipe = $ag['tipe'];
                                        $ap->anggota_id = $ag[$ag['tipe']];
                                        $ap->nama = $dts['nama'];
                                        $ap->jabatan = $dts['jabatan'];
                                        $ap->tipe_penindakan_id = $data['tipe_penindakan_id'];
                                        $ap->save();
                                    }
                                }

                                Notification::make()
                                    ->title('Laporan telah ditindak lanjuti oleh ' . $user->name)
                                    ->actions([
                                        Action::make('view')
                                            ->url($record->id)
                                            ->button()
                                            ->markAsUnread(),
                                    ])
                                    ->success()
                                    ->sendToDatabase(superAdmin())
                                    ->send();

                                autoSendWhatsapp($record->id, 'tindak_lanjut');
                            }
                        ),
                    Action::make('batal_laporan')
                        ->icon('tabler-x')
                        ->color(Color::Red)
                        ->authorize('batal_laporan::masyarakats::laporan::masyarakat')
                        ->disabled(fn($record): bool => $record->autorisasi(TipeAutorisasi::BATAL))
                        ->schema([
                            MarkdownEditor::make('deskripsi')
                                ->label('alasan')
                                ->required(),
                        ])
                        ->action(
                            function ($data, $record): void {
                                $user = Auth::user();
                                LaporanAutorisasi::create([
                                    'user_id' => $user->id,
                                    'laporan_masyarakat_id' => $record->id,
                                    'tipe_autorisasi' => TipeAutorisasi::BATAL,
                                    'tanggal_autorisasi' => Carbon::now(),
                                    'deskripsi' => $data['deskripsi'],
                                ]);

                                $record->status = TipeAutorisasi::BATAL;
                                $record->save();

                                Notification::make()
                                    ->title('Laporan telah dibatalkan oleh ' . $user->name)
                                    ->actions([
                                        Action::make('view')
                                            ->url($record->id)
                                            ->button()
                                            ->markAsUnread(),
                                    ])
                                    ->success()
                                    ->sendToDatabase(superAdmin())
                                    ->send();

                                autoSendWhatsapp($record->id, 'batal');
                            }
                        ),
                    Action::make('selesai_laporan')
                        ->icon('tabler-checks')
                        ->color(Color::Green)
                        ->authorize('selesai_laporan::masyarakats::laporan::masyarakat')
                        ->disabled(fn($record): bool => $record->autorisasi(TipeAutorisasi::SELESAI))
                        ->schema([
                            TextInput::make('url')
                                ->prefixIcon('tabler-link')
                                ->required(),
                            MarkdownEditor::make('deskripsi')
                                ->nullable(),

                        ])
                        ->hidden(function ($livewire): bool {
                            $record = $livewire->record;

                            if ($record->autorisasi(TipeAutorisasi::TINDAK_LANJUT)) {
                                return false;
                            }

                            return true;
                        })
                        ->action(
                            function ($data, $record): void {
                                $user = Auth::user();

                                $short = app(Builder::class)
                                    ->destinationUrl($data['url'])
                                    ->trackVisits()
                                    ->trackIPAddress()
                                    ->make();

                                LaporanAutorisasi::create([
                                    'user_id' => $user->id,
                                    'laporan_masyarakat_id' => $record->id,
                                    'tipe_autorisasi' => TipeAutorisasi::SELESAI,
                                    'tanggal_autorisasi' => Carbon::now(),
                                    'deskripsi' => $data['deskripsi'],
                                    'url' => $short->default_short_url,
                                ]);

                                $record->status = TipeAutorisasi::SELESAI;
                                $record->save();

                                Notification::make()
                                    ->title('Laporan telah diselesaikan oleh ' . $user->name)
                                    ->actions([
                                        Action::make('view')
                                            ->url($record->id)
                                            ->button()
                                            ->markAsUnread(),
                                    ])
                                    ->success()
                                    ->sendToDatabase(superAdmin())
                                    ->send();

                                autoSendWhatsapp($record->id, 'selesai');
                            }
                        ),
                ])
                    ->visible(fn($livewire): bool => $livewire->record->autorisasi(TipeAutorisasi::PROSES))
                    ->dropdown(false)
            ])
                ->button()
                ->hidden(function ($livewire): bool {
                    $record = $livewire->record;

                    if (!user()->hasRole('super_admin') && count($record->autorisasis)) {
                        return $record->autorisasi(TipeAutorisasi::BATAL);
                    }

                    return false;
                })
        ];
    }
}
