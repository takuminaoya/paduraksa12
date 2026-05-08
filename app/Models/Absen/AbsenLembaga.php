<?php

namespace App\Models\Absen;

use Illuminate\Database\Eloquent\Model;

class AbsenLembaga extends Model
{
    protected $connection = "absen";
    protected $primaryKey = "id";
    protected $table = "lembagas";
}
