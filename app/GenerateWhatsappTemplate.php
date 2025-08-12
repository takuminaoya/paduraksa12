<?php

namespace App;

use App\Models\ApplicationSetting;
use App\Models\LaporanMasyarakat;
use Illuminate\Support\Carbon;
use App\Models\WhatsappTemplate;

class GenerateWhatsappTemplate
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function generate()
    {
        $model = new LaporanMasyarakat();
        foreach ($model->whatsapp_templates as $tw) {
            $data = null;

            $input = [
                'slug' => $tw['slug'],
                'judul' => $tw['judul'],
                'isi' => 'inputkan isi disini',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            $check = WhatsappTemplate::where('slug', $tw['slug'])->first();

            if (!$check) {
                $data = WhatsappTemplate::create($input);
            } else {
                $data = $check;
            }

            $checkApp = ApplicationSetting::where('key', $tw['slug'])->first();
            if (!$checkApp) {
                $app_inputs = [
                    'key' => $tw['slug'],
                    'value' => $data->id,
                    'nama' => $data->judul,
                    'deskripsi' => $data->isi,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

                ApplicationSetting::create($app_inputs);
            }
        }
    }
}
