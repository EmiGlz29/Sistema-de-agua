<?php

use App\Http\Controllers\ReportesController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::get('/info_sistema', function () {
    return view('info_sistema');
})->middleware(['auth', 'verified'])->name('info_sistema');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::get('/crear-reportes', fn () => redirect()->route('reportes.create'))
        ->name('reportes.create.alias');
    Route::resource('reportes', ReportesController::class);
});

require __DIR__.'/settings.php';
