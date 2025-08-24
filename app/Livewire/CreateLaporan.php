<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Enum\BanjarEnum;
use App\Modules\Whapify;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Enum\KlasifikasiKode;
use Illuminate\Support\Carbon;
use App\Models\WhatsappLaporan;
use App\Enum\KlasifikasiLaporan;
use App\Models\Ungasan\Penduduk;
use App\Models\WhatsappTemplate;
use App\Models\LaporanMasyarakat;
use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class CreateLaporan extends Component implements HasSchemas, HasActions
{

    use InteractsWithSchemas;
    use InteractsWithActions;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
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
                            // Toggle::make('penyandang_disabilitas'),
                        ])
                        ->columns(2),
                ])->submitAction(new HtmlString('<button class="w-full py-2 px-5 rounded-lg bg-amber-400 hover:bg-amber-300" type="submit"><x-tabler-plus />Laporkan Tautan</button>'))

                    ->columnSpanFull()
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();
        $lap = LaporanMasyarakat::create($data);

        // generate kode
        $kode = "";
        foreach (KlasifikasiKode::cases() as $c) {
            if ($c->name == $lap->klasifikasi->value) {
                $kode = $c->value;
            }
        }

        $gen = $kode . "-" . date('dmy', strtotime($lap->created_at)) . $lap->id . "-UNGASAN";
        $lap->tiket = $gen;
        $lap->save();

        // kirim whatsapp
        autoSendWhatsapp($lap->id, 'aktif');

        $this->redirect('/notif/sukses/' . $lap->uuid, navigate: true);
    }

    public function render()
    {
        return view('livewire.create-laporan');
    }
}
