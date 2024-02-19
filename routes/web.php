<?php

use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use App\Models\Video;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $videos = Video::where('user_id', Auth::id())->latest('created_at')->get();

    return Inertia::render('Dashboard', [
        'videos' => $videos,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('video', VideoController::class)->middleware(['auth', 'verified']);
    Route::post('video/{video}/cover-image-upload', [VideoController::class, 'handleCoverImageUpload'])->name('video.cover-image-upload');

    Route::resource('person', PersonController::class)->middleware(['auth', 'verified']);

    Route::post('person/{person}/avatar-upload', [PersonController::class, 'handleAvatarUpload'])
        ->name('avatar-upload');
});

require __DIR__.'/auth.php';
