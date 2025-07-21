<?php

use App\Livewire\HalamanUtama;
use Illuminate\Support\Facades\Route;

Route::get('/', HalamanUtama::class)->name('home');
