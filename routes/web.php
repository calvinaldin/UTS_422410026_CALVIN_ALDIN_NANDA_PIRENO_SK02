<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PerlengkapanController;
use App\Http\Controllers\SesiPengamatanController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

// Resource Controller: index, create, store, show, edit, update, destroy
Route::resource('sesi-pengamatan', SesiPengamatanController::class)
    ->parameters(['sesi-pengamatan' => 'sesi']);

Route::resource('lokasi', LokasiController::class)
    ->parameters(['lokasi' => 'lokasi']);

Route::resource('perlengkapan', PerlengkapanController::class)
    ->parameters(['perlengkapan' => 'perlengkapan'])
    ->except('show');

Route::resource('kategori', KategoriController::class)
    ->parameters(['kategori' => 'kategori'])
    ->except('show');
