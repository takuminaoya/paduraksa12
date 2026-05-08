<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipePenindakan extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'butuh_anggota' => 'bool'
    ];
}
