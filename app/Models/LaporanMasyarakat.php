<?php

namespace App\Models;

use App\Enum\TipeAutorisasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaporanMasyarakat extends Model
{
    protected $guarded = ["id"];

    protected $casts = [
        'anonim' => 'boolean',
        'rahasia' => 'boolean',
        'penyandang_disabilitas' => 'boolean',

    ];

    public function autorisasis(): HasMany
    {
        return $this->hasMany(LaporanAutorisasi::class);
    }

    public function autorisasi(TipeAutorisasi $tipe): bool
    {
        $check = LaporanAutorisasi::where('tipe_autorisasi', $tipe)->where('laporan_masyarakat_id', $this->id)->first();

        return $check ? true : false;
    }
}
