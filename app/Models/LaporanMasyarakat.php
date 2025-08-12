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

    public $auto_whatsapp = true;
    public $whatsapp_templates = [
        'aktif' => [
            'slug' => 'wa-lm-regis',
            'judul' => 'Whatsapp Laporan Baru',
        ],
        'proses' => [
            'slug' => 'wa-lm-proses',
            'judul' => 'Whatsapp Laporan Diproses',
        ],
        'verifikasi' => [
            'slug' => 'wa-lm-verifikasi',
            'judul' => 'Whatsapp Laporan Verifikasi',
        ],
        'tindak_lanjut' => [
            'slug' => 'wa-lm-tindak_lanjut',
            'judul' => 'Whatsapp Laporan Ditindak lanjuti',
        ],
        'batal' => [
            'slug' => 'wa-lm-batal',
            'judul' => 'Whatsapp Laporan Dibatalkan',
        ],
        'selesai' => [
            'slug' => 'wa-lm-selesai',
            'judul' => 'Whatsapp Laporan Telah Selesai',
        ],
    ];

    public $prefix = '500.2';
    public $suffix = 'UNGASAN';
    public $separator = "/";

    public function nomorSurat(): string {
        
        if($this->autorisasi(TipeAutorisasi::SELESAI)){
            $aut = $this->getAutorisasiString(TipeAutorisasi::SELESAI, 'nomor_surat');

            $raws = [
                $this->prefix,
                $aut,
                $this->suffix
            ];

            return implode($this->separator, $raws);
        }

        return "-";
    }

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
            '[laporan.tindakan]',
            '[laporan.link]',
            '[laporan.url]',
            '[laporan.nomor]',
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
            '[laporan.tindakan]' => $this->getAutorisasiString(TipeAutorisasi::TINDAK_LANJUT, 'deskripsi'),
            '[laporan.link]' => asset('storage/' . $this->getAutorisasiString(TipeAutorisasi::PROSES, 'lampiran')),
            '[laporan.url]' => $this->url,
            '[laporan.nomor]' => $this->nomor_surat,

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
                '[laporan.tindakan]' => $data->getAutorisasiString(TipeAutorisasi::TINDAK_LANJUT, 'deskripsi'),
                '[laporan.link]' => asset('storage/' . $data->getAutorisasiString(TipeAutorisasi::PROSES, 'lampiran')),
                '[laporan.url]' => $data->url,
                '[laporan.nomor]' => $data->nomor_surat,

            ];
        } else {
            return [];
        }
    }

    public function reformatStringWithTag($string, $id = null): string
    {
        $strArray = explode("*", $string);
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

    public function autorisasi(TipeAutorisasi|string $tipe): bool
    {
        $check = LaporanAutorisasi::where('tipe_autorisasi', $tipe)->where('laporan_masyarakat_id', $this->id)->first();

        return $check ? true : false;
    }

    public function getAutorisasiString(TipeAutorisasi|string $tipe, $key = 'id'): string
    {
        $check = LaporanAutorisasi::where('tipe_autorisasi', $tipe)->where('laporan_masyarakat_id', $this->id)->first();

        return $check ? $check->{$key} : '-';
    }

    public function getAutorisasiLaporan($tipe) {
        $data = LaporanAutorisasi::where('tipe_autorisasi', $tipe)->where('laporan_masyarakat_id', $this->id)->first();
        
        if($data)
            return $data->reports;

        return [];
    }
}
