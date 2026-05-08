<?php

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
        Schema::table('laporan_autorisasis', function (Blueprint $table) {
            $table->foreignIdFor(TipePenindakan::class)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_autorisasis', function (Blueprint $table) {
            $table->dropColumn('tipe_penindakan_id');
        });
    }
};
