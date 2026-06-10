<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LostFound;

Route::get('/', [LostFound::class, 'index'])->name('katalog');
Route::post('/login', [LostFound::class, 'login'])->name('login.post');
Route::post('/report-found', [LostFound::class, 'store'])->name('report.found');
Route::delete('/items/{id}', [LostFound::class, 'destroy'])->name('items.destroy');
Route::post('/login-mahasiswa', [LostFound::class, 'loginMahasiswa'])->name('login.mahasiswa');
Route::get('/logout', [LostFound::class, 'logout'])->name('logout');