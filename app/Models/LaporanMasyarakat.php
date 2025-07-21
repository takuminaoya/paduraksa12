<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanMasyarakat extends Model
{
    protected $guarded = ["id"];

    protected $casts = [
        'anonim' => 'boolean',
        'rahasia' => 'boolean',
        'penyandang_disabilitas' => 'boolean',

    ];
}
