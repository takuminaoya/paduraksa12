<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Modules\Whapify;
use Filament\Actions\Action;
use GuzzleHttp\Psr7\Request;
use App\Models\WhatsappLaporan;
use App\Models\WhatsappTemplate;
use App\Models\LaporanMasyarakat;
use App\Models\ApplicationSetting;
use Filament\Notifications\Notification;

class HalamanNotifikasi extends Component
{
    public $laporan;

    public $no_telpon;

    public function mount($uuid)
    {
        $this->laporan = LaporanMasyarakat::where('uuid', $uuid)->first();
    }

    public function update(){
         $validated = $this->validate([ 
            'no_telpon' => 'required',
        ]);
 
        $this->laporan->no_telpon = $validated['no_telpon'];
        $this->laporan->save();

        $template = ApplicationSetting::getSettingValueByKey('wa-registrasi');
        $templateIsi = WhatsappTemplate::find($template);
        $reformatedIsi = $this->laporan->reformatStringWithTag($templateIsi->isi, $this->laporan->id);

        $message = Whapify::sendSingleChat('62' . $this->laporan->no_telpon, $reformatedIsi);

        if ($message) {
            $detail = Whapify::getSingleChat($message['messageId']);

            WhatsappLaporan::create([
                'laporan_masyarakat_id' => $this->laporan->id,
                'whatsapp_id' => $message['messageId'],
                'receipent' => $detail['recipient'],
                'isi_pesan' => $detail['message'],
                'dikirim_pada' => Carbon::createFromTimestamp($detail['created'])->toDateTimeString(),

            ]);

            $notif_route = url('admin/laporan-masyarakats/' . $this->laporan->id);

            Notification::make()
                ->title('Whatsapp dengan penerima ' . $this->laporan->nama . ' telah masuk queue.')
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

        session()->flash('status', 'Nomor Whatsapp telah berhasil di update. dan pesan otomatis telah dikirim kembali. mohon dicek di device anda masing-masing.');
    }

    public function render()
    {
        return view('livewire.halaman-notifikasi');
    }
}
