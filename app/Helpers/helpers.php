<?php

use App\Models\User;
use App\Modules\Whapify;
use Filament\Actions\Action;
use Illuminate\Support\Carbon;
use App\Models\WhatsappLaporan;
use App\Models\WhatsappTemplate;
use App\Models\LaporanMasyarakat;
use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;

function superAdmin(): User
{
    return User::find(1);
}

function user(): User
{
    return Auth::user();
}

// fungsi untuk mendapatkan nama hari berdasarkan index
// index itu dari 0 - 6 yang 0 adalah minggu
// language = bahasa hari yang dipergunakan kode berupa [id, en]
function getDayName($index, $language = "id", $dipersingkat = false)
{
    if ($language == "id") {
        $kumpulan_hari = [
            "minggu",
            "senin",
            "selasa",
            "rabu",
            "kamis",
            "jumat",
            "sabtu"
        ];
    }

    if ($language == "en") {
        $kumpulan_hari = [
            "sunday",
            "monday",
            "tuesday",
            "wednesday",
            "thursday",
            "friday",
            "saturday"
        ];
    }

    // jika dipersingkat true
    if ($dipersingkat)
        return limitLetter($kumpulan_hari[$index]);
    else
        return $kumpulan_hari[$index];
}

// fungsi untuk hapus huruf berdasarkan limit yang ditentukan
// misal sunday + limit(3) = sun
function limitLetter($string, $limit = 3)
{
    // ubah string jadi array
    $string_conts = str_split($string);

    // tempan menyimpan hasil
    $hasils = [];

    // loop
    for ($i = 0; $i < count($string_conts); $i++) {
        if ($i < $limit) {
            $hasils[] = $string_conts[$i];
        }
    }

    // ubah array to string
    return implode("", $hasils);
}

// fungsi untuk trim dan reformat string
// contoh : 823888222 jadi 823-888-222 atau 823/888/222
function trimReformat($string, $trimLimit = 3, $trimSimbol = "-")
{
    // ubah string jadi array
    $string_array = str_split($string, $trimLimit);

    return implode($trimSimbol, $string_array);
}

// fungsi untuk menambahkan anggka 0 pada nomor
// contoh 1 + addingZero(3) = 001
function addingZero($value, $length = 4)
{
    $str = substr("0000{$value}", -$length);

    return $str;
}

// fungsi untuk mendapatkan bulan berdasarkan index
function getBulan($index, $dipersingkat = false, $lang = "id")
{
    if ($lang == "id") {
        $kumpulanBulan = [
            "januari",
            "februari",
            "maret",
            "april",
            "mei",
            "juni",
            "juli",
            "agustus",
            "september",
            "oktober",
            "november",
            "desember"
        ];
    }

    if ($lang == "en") {
        $kumpulanBulan = [
            "january",
            "february",
            "march",
            "april",
            "may",
            "june",
            "july",
            "august",
            "september",
            "october",
            "november",
            "december"
        ];
    }

    // jika dipersingkat true
    if ($dipersingkat)
        return limitLetter($kumpulanBulan[$index - 1]);
    else
        return $kumpulanBulan[$index - 1];
}

// fungsi untuk reformat tanggal
// hasil target dari 2023-04-05 jadi 05 Feb 2023 atau Senin, 02 February 2023
function dateReformat($tanggal, $tampilkanHari = false, $bulanDipersingkat = false, $pemisah = " ", $lang = "id", $formatTanggal = false, $tambahkanJam = false)
{
    $tanggal_totime = strtotime($tanggal);
    $date = date("d", $tanggal_totime);
    $bulan = date("m", $tanggal_totime);
    $tahun = date("Y", $tanggal_totime);

    // gabungkan value diatas jadi tanggal
    if ($formatTanggal) {
        return date($formatTanggal, $tanggal_totime);
    } else {
        $pukul = "";
        if ($tambahkanJam) {
            $pukul = " " . date("h:i A", $tanggal_totime);
        }

        return ($tampilkanHari) ? getDayname(date("w", $tanggal_totime)) . ", " . $date . $pemisah . getBulan($bulan, $bulanDipersingkat, $lang) . $pemisah . $tahun : $date . $pemisah . getBulan($bulan, $bulanDipersingkat, $lang) . $pemisah . $tahun . $pukul;
    }
}

function autoSendWhatsapp($id, $status)
{
    $lap = LaporanMasyarakat::find($id);

    if ($lap->auto_whatsapp) {
        $template = ApplicationSetting::where('key', $lap->whatsapp_templates[$status]['slug'])->value('value');

        $templateIsi = WhatsappTemplate::find($template);

        $reformatedIsi = $lap->reformatStringWithTag($templateIsi->isi, $lap->id);

        $message = Whapify::sendSingleChat('62' . $lap->no_telpon, $reformatedIsi);

        if ($message) {
            $detail = Whapify::getSingleChat($message['messageId']);

            WhatsappLaporan::create([
                'laporan_masyarakat_id' => $lap->id,
                'whatsapp_id' => $message['messageId'],
                'receipent' => $detail['recipient'],
                'isi_pesan' => $detail['message'],
                'dikirim_pada' => Carbon::createFromTimestamp($detail['created'])->toDateTimeString(),

            ]);

            $notif_route = url('admin/laporan-masyarakats/' . $lap->id);

            Notification::make()
                ->title('Whatsapp dengan penerima ' . $lap->nama . ' telam masuk queue.')
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
        } else {
            Notification::make()
                ->title('Nomor Whatsapp pemohon invalid. mohon pastikan nomor whatsapp telah benar. format seperti ini tanpa 0 didepannya cth: 82345678776')
                ->danger()
                ->actions([
                    Action::make('ubah')
                        ->icon('tabler-edit')
                        ->color(Color::Amber)
                        ->url(url('admin/laporan-masyarakats/'.$lap->id.'/edit'))
                ])
                ->sendToDatabase(Auth::user());
        }
    }
}
