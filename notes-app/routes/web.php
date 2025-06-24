<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('notes.index');
});

Route::get('/dashboard', function () {
    return redirect()->route('notes.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/notes/reorder', [NoteController::class, 'reorder'])->name('notes.reorder');
Route::post('/notes/{note}/pin', [NoteController::class, 'togglePin'])->name('notes.togglePin');
Route::post('/notes/{note}/position', [NoteController::class, 'updatePosition'])->name('notes.updatePosition');
Route::post('/profile/avatar', [App\Http\Controllers\ProfileController::class, 'updateAvatar'])
    ->name('profile.avatar')
    ->middleware('auth');
Route::middleware(['auth'])->group(function () {
    Route::post('/notes/{note}/position', [NoteController::class, 'updatePosition'])->name('notes.updatePosition');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('notes', NoteController::class)->except(['show']);
    // Profile routes générées par Breeze :
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
