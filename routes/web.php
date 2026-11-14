<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\AdminController;

Route::get('/', [GameController::class, 'home'])->name('home');
Route::get('/bao-vat', [GameController::class, 'treasures'])->name('treasures');
Route::get('/bao-vat/{id}', [GameController::class, 'treasureDetail'])->where('id', 'I01-[0-9]{4}')->name('treasure.show');
Route::get('/hoc-tap', [GameController::class, 'learning'])->name('learning');
Route::get('/mini-game', [GameController::class, 'miniGames'])->name('minigames');
Route::get('/tro-choi', [GameController::class, 'quiz'])->name('quiz');
Route::get('/the-ghi-nho', [GameController::class, 'flashcards'])->name('flashcards');
Route::get('/bang-diem', [ScoreController::class, 'index'])->name('scores');
Route::post('/luu-ket-qua', [ScoreController::class, 'store'])->middleware('throttle:20,1')->name('scores.store');
Route::get('/nguon-tu-lieu', [GameController::class, 'sources'])->name('sources');
Route::get('/gioi-thieu', [GameController::class, 'about'])->name('about');
Route::get('/xuat-du-lieu/json', [GameController::class, 'exportJson'])->name('export.json');
Route::get('/xuat-du-lieu/csv', [GameController::class, 'exportCsv'])->name('export.csv');
Route::post('/ai/giai-thich', [GameController::class, 'explain'])->middleware('throttle:10,1')->name('ai.explain');


Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/dang-nhap', [AuthController::class, 'login'])->name('login.store');
    Route::get('/dang-ky', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/dang-ky', [AuthController::class, 'register'])->name('register.store');
});
Route::post('/dang-xuat', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('quan-tri')->name('admin.')->middleware(['auth','admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/bao-vat', [AdminController::class, 'index'])->name('treasures');
    Route::get('/bao-vat/tao-moi', [AdminController::class, 'create'])->name('treasures.create');
    Route::post('/bao-vat', [AdminController::class, 'store'])->name('treasures.store');
    Route::get('/bao-vat/{id}/sua', [AdminController::class, 'edit'])->name('treasures.edit');
    Route::put('/bao-vat/{id}', [AdminController::class, 'update'])->name('treasures.update');
    Route::delete('/bao-vat/{id}', [AdminController::class, 'destroy'])->name('treasures.destroy');
});
