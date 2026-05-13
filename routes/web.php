<?php

use App\Http\Controllers\HalamanPublikSosmed;
use App\Http\Controllers\PrintTestController;
use App\Livewire\HalamanLaporan;
use App\Livewire\HalamanNotifikasi;
use App\Livewire\HalamanUtama;
use App\Livewire\PetujukLaporan;
use App\Livewire\PublikAkses;
use App\Livewire\TentangLaporan;
use Illuminate\Support\Facades\Route;

Route::get('/', HalamanUtama::class)->name('home');
Route::get('/notif/sukses/{uuid}', HalamanNotifikasi::class)->name('notif.sukses');
Route::get('/tentang', TentangLaporan::class)->name('tentang');
Route::get('/petunjuk', PetujukLaporan::class)->name('petunjuk');
Route::get('/publik', PublikAkses::class)->name('publik');
Route::get('/daftar', HalamanLaporan::class)->name('daftar');

Route::get('print/preview/{id}', [PrintTestController::class, 'preview']);
Route::get('tanggapan/preview/{id}', [PrintTestController::class, 'tanggapan'])->name('preview.tanggapan');

Route::get('public/preview/{uuid}', [PrintTestController::class, 'viewLaporanPublic']);

// untuk sosmed tim
Route::prefix('sosmed/watch')->group(function() {
    Route::get('/', [HalamanPublikSosmed::class, 'index'])->name('sosmed');
});

