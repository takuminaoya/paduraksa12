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
use CodeWithDennis\FilamentAdvancedChoice\Filament\Forms\Components\CheckboxStackedCard;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
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

                                autoSendWhatsapp($record->id, null, $record->no_telpon, $record->getTemplate('verifikasi')['slug']);
                            }
                        ),
                    Action::make('tindak_lanjut_laporan')
                        ->icon('tabler-trekking')
                        ->color(Color::Emerald)
                        ->authorize('tindak_lanjut_laporan::masyarakats::laporan::masyarakat')
                        ->disabled(fn($record): bool => $record->autorisasi(TipeAutorisasi::TINDAK_LANJUT))
                        ->modalWidth(Width::SixExtraLarge)
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Select::make('tipe_penindakan_id')
                                        ->searchable()
                                        ->live()
                                        ->options(TipePenindakan::query()->pluck('nama', 'id')),
                                    DatePicker::make('tanggal_kegiatan')
                                        ->prefixIcon('tabler-calendar')
                                        ->required(),
                                    TimePicker::make('jam_kegiatan')
                                        ->prefixIcon('tabler-clock')
                                        ->native(false)
                                        ->seconds(false)
                                        ->required(),
                                    // Jika dibutuhkan anggota
                                    Section::make('Daftar Staff Aparatur Desa')
                                        ->visible(
                                            function ($get) {
                                                if($get('tipe_penindakan_id')){
                                                    $penindakan = TipePenindakan::find($get('tipe_penindakan_id'));

                                                    if($penindakan->butuh_anggota == 1){
                                                        return true;
                                                    } else {
                                                        return false;
                                                    }
                                                }

                                                return false;
                                            }
                                        )
                                        ->collapsible()
                                        ->columnSpanFull()
                                        ->schema([
                                            CheckboxStackedCard::make('anggotas')
                                                ->hiddenLabel()
                                                ->live()
                                                ->columnSpanFull()
                                                ->columns(3)
                                                ->afterStateUpdated(
                                                    function ($set, $state) {
                                                        $set('jumlah_staff', count($state));
                                                    }
                                                )
                                                ->options(
                                                    function () : array {
                                                        $res = [];
                                                        $staffs = AbsenUser::all();

                                                        foreach($staffs as $s){
                                                            $res[$s->id] = $s->name;
                                                        }

                                                        return $res;
                                                    }
                                                )
                                                ->descriptions(
                                                    function () : array {
                                                        $res = [];
                                                        $staffs = AbsenUser::all();

                                                        foreach($staffs as $s){
                                                            $res[$s->id] = DB::connection('absen')->table('pengguna_jabatans')->select('nama')->where('id', $s->jabatan_id)->value('nama');
                                                        }

                                                        return $res;
                                                    }
                                                )
                                                ->searchable()
                                                ->bulkToggleable()
                                        ]),
                                    Section::make('Daftar Lembaga Desa')
                                        ->visible(
                                            function ($get) {
                                                if($get('tipe_penindakan_id')){
                                                    $penindakan = TipePenindakan::find($get('tipe_penindakan_id'));

                                                    if($penindakan->butuh_anggota == 1){
                                                        return true;
                                                    } else {
                                                        return false;
                                                    }
                                                }

                                                return false;
                                            }
                                        )
                                        ->collapsible()
                                        ->columnSpanFull()
                                        ->schema([
                                            CheckboxStackedCard::make('lembagas')
                                                ->hiddenLabel()
                                                ->columnSpanFull()
                                                ->columns(3)
                                                ->live()
                                                ->afterStateUpdated(
                                                    function ($set, $state) {
                                                        $set('jumlah_lembaga', count($state));
                                                    }
                                                )
                                                ->options(
                                                    function () : array {
                                                        $res = [];
                                                        $staffs = AbsenLembaga::all();

                                                        foreach($staffs as $s){
                                                            $res[$s->id] = $s->nama_orang;
                                                        }

                                                        return $res;
                                                    }
                                                )
                                                ->descriptions(
                                                    function () : array {
                                                        $res = [];
                                                        $staffs = AbsenLembaga::all();

                                                        foreach($staffs as $s){
                                                            $res[$s->id] = $s->nama;
                                                        }

                                                        return $res;
                                                    }
                                                )
                                                ->searchable()
                                                ->bulkToggleable()
                                        ]),
                                    TextInput::make('jumlah_staff')
                                        ->visible(
                                            function ($get) {
                                                if($get('tipe_penindakan_id')){
                                                    $penindakan = TipePenindakan::find($get('tipe_penindakan_id'));

                                                    if($penindakan->butuh_anggota == 1){
                                                        return true;
                                                    } else {
                                                        return false;
                                                    }
                                                }

                                                return false;
                                            }
                                        )
                                        ->live()
                                        ->disabled()
                                        ->suffix('Apratur'),
                                    TextInput::make('jumlah_lembaga')
                                        ->visible(
                                            function ($get) {
                                                if($get('tipe_penindakan_id')){
                                                    $penindakan = TipePenindakan::find($get('tipe_penindakan_id'));

                                                    if($penindakan->butuh_anggota == 1){
                                                        return true;
                                                    } else {
                                                        return false;
                                                    }
                                                }

                                                return false;
                                            }
                                        )
                                        ->live()
                                        ->disabled()
                                        ->suffix('Lembaga'),
                                    MarkdownEditor::make('deskripsi')
                                        ->columnSpanFull()
                                        ->required(),
                                    Textarea::make('titik_kumpul')
                                        ->columnSpanFull()
                                        ->required()
                                        ->rows(3)
                                    ])

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
                                try {
                                    $templateSlug = "whatsapp-anggota-penanganan";

                                    $user = Auth::user();
                                    LaporanAutorisasi::create([
                                        'user_id' => $user->id,
                                        'laporan_masyarakat_id' => $record->id,
                                        'tipe_autorisasi' => TipeAutorisasi::TINDAK_LANJUT,
                                        'tanggal_autorisasi' => Carbon::now(),
                                        'deskripsi' => $data['deskripsi'],
                                        'tipe_penindakan_id' => $data['tipe_penindakan_id'],
                                        'tanggal_kegiatan' => $data['tanggal_kegiatan'],
                                        'titik_kumpul' => $data['titik_kumpul'],
                                    ]);

                                    $record->status = TipeAutorisasi::TINDAK_LANJUT;
                                    $record->save();

                                    // simpan anggota jika tipe membutuhkan 
                                    $penindakan = TipePenindakan::find($data['tipe_penindakan_id']);
                                    if ($penindakan->butuh_anggota == true) {
                                        if(count($data['anggotas']) > 0) {
                                            foreach ($data['anggotas'] as $ag) {

                                                $dt = AbsenUser::find($ag);
                                                $j = DB::connection('absen')->table('pengguna_jabatans')->where('id', $dt->jabatan_id)->first();
                                                $dts = [
                                                    'nama' => $dt->name,
                                                    'jabatan' => $j->nama
                                                ];

                                                autoSendWhatsapp($record->id, null, $dt->phone, 'whatsapp-anggota-penanganan', null);


                                                $ap = new AnggotaPenindakan();
                                                $ap->laporan_masyarakat_id = $record->id;
                                                $ap->tipe = 'staff';
                                                $ap->anggota_id = $ag;
                                                $ap->nama = $dts['nama'];
                                                $ap->jabatan = $dts['jabatan'];
                                                $ap->tipe_penindakan_id = $data['tipe_penindakan_id'];
                                                $ap->save();
                                            }

                                            foreach ($data['lembagas'] as $lmb) {

                                                $lmbb = AbsenLembaga::find($lmb);
                                                $lmbs = [
                                                    'nama' => $lmbb->nama_orang,
                                                    'jabatan' => $lmbb->nama
                                                ];

                                                autoSendWhatsapp($record->id, null, $lmbb->no_telp, 'whatsapp-anggota-penanganan', null);


                                                $ap = new AnggotaPenindakan();
                                                $ap->laporan_masyarakat_id = $record->id;
                                                $ap->tipe = 'lembaga';
                                                $ap->anggota_id = $lmb;
                                                $ap->nama = $lmbs['nama'];
                                                $ap->jabatan = $lmbs['jabatan'];
                                                $ap->tipe_penindakan_id = $data['tipe_penindakan_id'];
                                                $ap->save();
                                            }
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

                                        autoSendWhatsapp($record->id, null, $record->no_telpon, $record->getTemplate('tindak_lanjut')['slug']);

                                } catch (Exception $e){
                                    dd($e);
                                    Notification::make()
                                        ->title('Sistem Error')
                                        ->body('Terjadi kesalahan pada sistem.')
                                        ->danger()
                                        ->send();
                                }
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

                                    autoSendWhatsapp($record->id, null, $record->no_telpon, $record->getTemplate('batal')['slug']);

                            }
                        ),
                    Action::make('selesai_laporan')
                        ->icon('tabler-checks')
                        ->color(Color::Green)
                        ->authorize('selesai_laporan::masyarakats::laporan::masyarakat')
                        ->disabled(fn($record): bool => $record->autorisasi(TipeAutorisasi::SELESAI))
                        ->schema([
                            Hidden::make('url')
                                ->default(fn ($record) => route('preview.tanggapan', ["id" => $record->id]))
                                ->required(),
                            MarkdownEditor::make('deskripsi')
                                ->required(),
                            TextInput::make('nomor_surat')
                                ->prefixIcon('tabler-number')
                                ->readOnly()
                                ->default(fn ($record) => $record->id)
                                ->required(),
                            FileUpload::make('lampiran')
                                ->optimize()
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
                                    'lampiran' => $data['lampiran'],
                                    'nomor_surat' => $data['nomor_surat'],
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

                                    autoSendWhatsapp($record->id, null, $record->no_telpon, $record->getTemplate('selesai')['slug']);

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
