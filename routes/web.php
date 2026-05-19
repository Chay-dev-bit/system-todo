<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Employed\Jabatan;
use App\Livewire\Employed\Kantor;
use App\Livewire\Employed\Pengguna;
use App\Livewire\Employed\Pegawai;
use App\Livewire\Employed\Unit;
use App\Livewire\Employed\Role;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

// Route::view('/', 'login');
// karena pakai volt jadi harus gini
Route::get('/', function () {

    return auth()->check()

        ? redirect()->route('dashboard')

        : redirect('/login');

});

/*
|--------------------------------------------------------------------------
| Authenticated Route
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');

    Route::view('/profile', 'profile')
        ->name('profile');

    /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    */

    Route::get('/jabatan', Jabatan::class)
        ->name('jabatan');

    Route::get('/kantor', Kantor::class)
        ->name('kantor');

    Route::get('/unit', Unit::class)
        ->name('unit');

    Route::get('/pengguna', Pengguna::class)
        ->name('pengguna');

    Route::get('/role', Role::class)
        ->name('role');

    Route::get('/pegawai', Pegawai::class)
        ->name('pegawai');
});

require __DIR__ . '/auth.php';