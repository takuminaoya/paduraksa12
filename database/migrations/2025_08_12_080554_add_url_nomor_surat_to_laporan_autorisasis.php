<?php

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
            $table->longText('url')->nullable()->after('lampiran');
            $table->string('nomor_surat')->nullable()->after('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_autorisasis', function (Blueprint $table) {
            $table->dropColumn('url');
            $table->dropColumn('nomor_surat');
        });
    }
};
