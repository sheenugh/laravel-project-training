<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth'])->group(function () {
    Route::view('/sub-content', 'components.pages.main-content.sub-content.⚡index')->name('sub-content.index');
    Route::view('/sub-content/create', 'components.pages.main-content.sub-content.⚡create')->name('sub-content.create');
    Route::view('/sub-content/view', 'components.pages.main-content.sub-content.⚡view')->name('sub-content.view');
    Route::view('/sub-content/edit', 'components.pages.main-content.sub-content.⚡edit')->name('sub-content.edit');
    Route::view('/sub-content/delete', 'components.pages.main-content.sub-content.⚡delete')->name('sub-content.delete');
    Route::view('/activity-logs', 'components.pages.activity-logs.index')->name('activity-logs.index');
});

require __DIR__.'/auth.php';
