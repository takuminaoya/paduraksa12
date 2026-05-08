<?php

namespace App\Models\Absen;

use Illuminate\Database\Eloquent\Model;

class AbsenUser extends Model
{
    protected $connection = "absen";
    protected $primaryKey = "id";
    protected $table = "users";
}
