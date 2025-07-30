<?php

namespace App\Filament\Resources\LaporanMasyarakats\RelationManagers;

use BackedEnum;
use App\Modules\Whapify;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use App\Models\WhatsappLaporan;
use App\Models\WhatsappTemplate;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Colors\Color;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Actions\DissociateBulkAction;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use phpDocumentor\Reflection\DocBlock\Tags\Since;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Set;

class WhatsappsRelationManager extends RelationManager
{
    protected static string $relationship = 'whatsapps';
    protected static ?string $title = 'Daftar Pesan Whatsapp';
    protected static string | BackedEnum | null $icon = 'tabler-brand-whatsapp';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('receipent')
            ->description('Serangkaian pesan yang telah disusun dan dikategorikan untuk digunakan dalam komunikasi yang bersifat resmi, otomatis, atau berulang melalui aplikasi WhatsApp.')
            ->columns([
                TextColumn::make('receipent')
                    ->label('Nomor Penerima')
                    ->searchable(),
                TextColumn::make('isi_pesan')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('dikirim_pada')
                    ->since()
                    ->searchable(),
                TextColumn::make('stat')
                    ->label('Status')
                    ->badge()
                    ->default(fn($record): string => $record->status())
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('kirim_pesan')
                    ->icon('tabler-phone')
                    ->color(Color::Emerald)
                    ->schema([
                        TextInput::make('receipent')
                            ->prefix("+62")
                            ->required()
                            ->readOnly()
                            ->default(fn($livewire): string => $livewire->ownerRecord->no_telpon)
                            ->label('Nomor Penerima'),
                        Select::make('template')
                            ->options(WhatsappTemplate::all()->pluck('judul', 'id'))
                            ->live()
                            ->afterStateUpdated(
                                function (Set $set, ?string $state) {
                                    $wt = WhatsappTemplate::find($state)->isi;

                                    $set('isi_pesan', $wt);
                                }
                            ),
                        Textarea::make('isi_pesan')
                            ->autosize()
                            ->required()
                    ])
                    ->action(
                        function ($data, $livewire): void {
                            $recordMaster = $livewire->ownerRecord;
                            $reformatedIsi = $recordMaster->reformatStringWithTag($data['isi_pesan']);

                            $message = Whapify::sendSingleChat('62' . $data['receipent'], $reformatedIsi);
                            if ($message) {
                                $detail = Whapify::getSingleChat($message['messageId']);
                                WhatsappLaporan::create([
                                    'laporan_masyarakat_id' => $livewire->ownerRecord->id,
                                    'whatsapp_id' => $message['messageId'],
                                    'receipent' => $detail['recipient'],
                                    'isi_pesan' => $detail['message'],
                                    'dikirim_pada' => Carbon::createFromTimestamp($detail['created'])->toDateTimeString(),

                                ]);

                                $notif_route = url('admin/laporan-masyarakats/' . $livewire->ownerRecord->id);

                                Notification::make()
                                    ->title('Whatsapp dengan penerima ' . $data['receipent'] . ' telam masuk queue.')
                                    ->actions([
                                        Action::make('lihat_laporan')
                                            ->icon('tabler-eye')
                                            ->url($notif_route)
                                            ->button()
                                            ->markAsUnread(),
                                    ])
                                    ->success()
                                    ->sendToDatabase(superAdmin())
                                    ->send();
                            }
                        }
                    )
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
