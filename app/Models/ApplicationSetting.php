<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationSetting extends Model
{
    protected $guarded = ["id"];

    public static function getSettingValueByKey(string $key) {
        return ApplicationSetting::where('key', $key)->value('value');
    }
}
