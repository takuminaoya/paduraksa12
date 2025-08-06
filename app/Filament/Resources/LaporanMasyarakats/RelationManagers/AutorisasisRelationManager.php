<?php

namespace App\Filament\Resources\LaporanMasyarakats\RelationManagers;

use BackedEnum;
use Carbon\Carbon;
use Filament\Tables\Table;
use App\Enum\TipeAutorisasi;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Models\ReportAutorisasi;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Colors\Color;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Schemas\Components\Grid;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Actions\DissociateBulkAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Resources\RelationManagers\RelationManager;

class AutorisasisRelationManager extends RelationManager
{
    protected static string $relationship = 'autorisasis';
    protected static ?string $title = 'Daftar Autorisasi Dokumen';
    protected static string | BackedEnum | null $icon = 'tabler-user';

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
                ActionGroup::make([
                    DeleteAction::make()
                        ->hidden(fn(): bool => !user()->hasRole('super_admin')),
                    Action::make('download_lampiran')
                        ->label('Download Lampiran')
                        ->icon('tabler-download')
                        ->visible(fn($record): bool => $record->lampiran ? true : false)
                        ->action(
                            function ($record) {

                                foreach ($record->lampiran as $lamp) {
                                    return Storage::disk('public')->download($lamp);
                                }
                            }
                        )
                        ->openUrlInNewTab(),
                    ActionGroup::make([
                        Action::make('laporan')
                            ->icon('tabler-plus')
                            ->schema([
                                TextInput::make('judul')
                                    ->required(),
                                DatePicker::make('tanggal')
                                    ->default(Carbon::now())
                                    ->required(),
                                MarkdownEditor::make('deskripsi')
                                    ->required(),
                                FileUpload::make('lampiran')
                                    ->disk('public')
                                    ->directory('auth_lampiran')
                                    ->visibility('public')
                                    ->nullable()
                            ])
                            ->action(
                                function ($data, $record): void {



                                    $inputs = [
                                        "laporan_autorisasi_id" => $record->id,
                                        "tanggal" => $data['tanggal'],
                                        "judul" => $data['judul'],
                                        "deskripsi" => $data['deskripsi'],
                                        "lampiran" => $data['lampiran'],
                                    ];

                                    ReportAutorisasi::create($inputs);

                                    Notification::make()
                                        ->title('Laporan telah berhasil disubmit.')
                                        ->success()
                                        ->send();
                                }
                            ),
                        ViewAction::make('view')
                            ->modalDescription('Dokumen yang mencatat dan menyajikan informasi mengenai pemberian izin atau persetujuan atas suatu tindakan, transaksi, atau akses tertentu dalam sebuah sistem atau organisasi.')
                            ->slideOver()
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('laporan.judul')
                                            ->label('Untuk Laporan'),
                                        TextEntry::make('tanggal_autorisasi')
                                            ->date(),
                                        TextEntry::make('tipe_autorisasi')
                                            ->badge(),
                                        TextEntry::make('deskripsi')
                                            ->columnSpanFull()
                                            ->html(),
                                        ImageEntry::make('lampiran')
                                            ->columnSpanFull()
                                            ->disk('public')
                                            ->url(fn($state): string => asset('storage/' . $state))
                                            ->openUrlInNewTab()
                                            ->imageHeight(250),
                                        RepeatableEntry::make('reports')
                                            ->label('Daftar Laporan Per Autorisasi')
                                            ->columnSpanFull()
                                            ->schema([
                                                TextEntry::make('tanggal')
                                                    ->icon('tabler-calendar')
                                                    ->date(),
                                                TextEntry::make('judul'),
                                                TextEntry::make('deskripsi')
                                                    ->icon('tabler-abc')
                                                    ->columnSpanFull(),
                                                ImageEntry::make('lampiran')
                                                    ->url(fn($state): string => asset('storage/' . $state))
                                                    ->openUrlInNewTab()
                                                    ->columnSpanFull()
                                                    ->imageHeight(250)
                                                    ->disk('public'),
                                                Action::make('hapus')
                                                    ->color(Color::Red)
                                                    ->button()
                                                    ->icon('tabler-x')
                                                    ->requiresConfirmation()
                                                    ->action(
                                                        function ($record) {
                                                            $record->delete();

                                                            Notification::make()
                                                                ->title('Laporan telah berhasil dihapus')
                                                                ->danger()
                                                                ->send();
                                                        }
                                                    ),
                                            ])
                                            ->columns(2)
                                    ])
                            ])
                    ])
                        ->dropdown(false)
                ])->dropdownPlacement('top-end')
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
