<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PhotoboothController;
use Illuminate\Support\Facades\Route;

// Public Pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/how-it-works', [PageController::class, 'howItWorks'])->name('how-it-works');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Photobooth Camera & Result Process
Route::get('/camera', [PhotoboothController::class, 'camera'])->name('camera');
Route::post('/photo/save', [PhotoboothController::class, 'savePhoto'])->name('photo.save');
Route::get('/photo/{id}/result', [PhotoboothController::class, 'result'])->name('photo.result');

// Admin Panel Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/photos', [AdminController::class, 'photos'])->name('photos');
    Route::delete('/photos/{id}', [AdminController::class, 'deletePhoto'])->name('photos.delete');
    Route::get('/photos/{id}/download', function (int $id) {
        $photo = \App\Models\Photo::findOrFail($id);
        $path = storage_path('app/public/' . ltrim($photo->result_image_path, '/'));
        abort_unless(file_exists($path), 404);
        return response()->download($path, 'holdmoment-' . $photo->id . '.png');
    })->name('photos.download');
    Route::get('/templates', [AdminController::class, 'templates'])->name('templates');
    Route::post('/templates', [AdminController::class, 'storeTemplate'])->name('templates.store');
    Route::get('/templates/{id}/edit', [AdminController::class, 'editTemplate'])->name('templates.edit');
    Route::put('/templates/{id}', [AdminController::class, 'updateTemplate'])->name('templates.update');
    Route::delete('/templates/{id}', [AdminController::class, 'destroyTemplate'])->name('templates.destroy');
    Route::patch('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
});
