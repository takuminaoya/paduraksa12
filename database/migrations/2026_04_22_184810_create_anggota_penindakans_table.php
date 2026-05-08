<?php

use App\Models\Absen\AbsenUser;
use App\Models\LaporanMasyarakat;
use App\Models\TipePenindakan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anggota_penindakans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LaporanMasyarakat::class)->constrained()->cascadeOnDelete();
            $table->enum('tipe', [
                'staff',
                'lembaga'
            ])->default('staff');
            $table->unsignedBigInteger('anggota_id')->nullable();
            $table->string('nama');
            $table->string('jabatan');
            $table->foreignIdFor(TipePenindakan::class)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_penindakans');
    }
};
