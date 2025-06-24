<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/notes');

Route::redirect('/dashboard', '/notes')->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Notes routes
    Route::resource('notes', NoteController::class)->except(['show']);

    Route::post('/notes/reorder', [NoteController::class, 'reorder'])->name('notes.reorder');
    Route::post('/notes/{note}/pin', [NoteController::class, 'togglePin'])->name('notes.togglePin');
    Route::post('/notes/{note}/position', [NoteController::class, 'updatePosition'])->name('notes.updatePosition');

    // Profile routes (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Avatar update route
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
});

require __DIR__.'/auth.php';
