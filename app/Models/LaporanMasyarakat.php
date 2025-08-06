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

    public static function getListOfTagsOnly(): array
    {
        return [
            '[laporan.tiket]',
            '[laporan.klasifikasi]',
            '[laporan.judul]',
            '[laporan.isi]',
            '[laporan.tanggal_kejadian]',
            '[laporan.lokasi_kejadian]',
            '[laporan.banjar_kejadian]',
            '[laporan.anonim]',
            '[laporan.judul]',
            '[laporan.nama]',
            '[laporan.uuid]',
            '[laporan.created_at]',
            '[laporan.alasan]',
        ];
    }

    public function getListOfTags(): array
    {
        return [
            '[laporan.tiket]' => $this->tiket,
            '[laporan.klasifikasi]' => $this->klasifikasi,
            '[laporan.judul]' => $this->judul,
            '[laporan.isi]' => $this->isi,
            '[laporan.tanggal_kejadian]' => $this->tanggal_kejadian,
            '[laporan.lokasi_kejadian]' => $this->lokasi_kejadian,
            '[laporan.banjar_kejadian]' => $this->banjar_kejadian,
            '[laporan.anonim]' => $this->anonim,
            '[laporan.judul]' => $this->judul,
            '[laporan.nama]' => $this->nama,
            '[laporan.uuid]' => $this->uuid,
            '[laporan.created_at]' => $this->created_at,
            '[laporan.alasan]' => $this->getAutorisasiString(TipeAutorisasi::BATAL, 'deskripsi'),
        ];
    }

    public function getListOfTagById($id): array
    {
        $data = LaporanMasyarakat::find($id);

        if ($data) {
            return [
                '[laporan.tiket]' => $data->tiket,
                '[laporan.klasifikasi]' => $data->klasifikasi,
                '[laporan.judul]' => $data->judul,
                '[laporan.isi]' => $data->isi,
                '[laporan.tanggal_kejadian]' => $data->tanggal_kejadian,
                '[laporan.lokasi_kejadian]' => $data->lokasi_kejadian,
                '[laporan.banjar_kejadian]' => $data->banjar_kejadian,
                '[laporan.anonim]' => $data->anonim,
                '[laporan.judul]' => $data->judul,
                '[laporan.nama]' => $data->nama,
                '[laporan.uuid]' => $data->uuid,
                '[laporan.created_at]' => $data->created_at,
                '[laporan.alasan]' => $data->getAutorisasiString(TipeAutorisasi::BATAL, 'deskripsi'),
            ];
        } else {
            return [];
        }
    }

    public function reformatStringWithTag($string, $id = null): string
    {
        $strArray = explode(" ", $string);
        $results = [];

        if ($id) {
            $tags = $this->getListOfTagById($id);
        } else {
            $tags = $this->getListOfTags();
        }

        foreach ($strArray as $str) {
            if (array_key_exists($str, $tags)) {
                $results[] = "*" . $tags[$str] . "*";
            } else {
                $results[] = $str;
            }
        }

        return implode(" ", $results);
    }

    public function autorisasis(): HasMany
    {
        return $this->hasMany(LaporanAutorisasi::class);
    }

    public function whatsapps(): HasMany
    {
        return $this->hasMany(WhatsappLaporan::class);
    }

    public function autorisasi(TipeAutorisasi $tipe): bool
    {
        $check = LaporanAutorisasi::where('tipe_autorisasi', $tipe)->where('laporan_masyarakat_id', $this->id)->first();

        return $check ? true : false;
    }

    public function getAutorisasiString(TipeAutorisasi $tipe, $key = 'id'): string
    {
        $check = LaporanAutorisasi::where('tipe_autorisasi', $tipe)->where('laporan_masyarakat_id', $this->id)->first();

        return $check ? $check->{$key} : '-';
    }
}
