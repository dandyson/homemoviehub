<?php

use App\Http\Controllers\PersonController;
use App\Http\Controllers\VideoController;
use App\Models\Video;
use Illuminate\Foundation\Application;
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
    if (! auth()->check()) {
        return Inertia::render('Welcome', [
            'laravelVersion' => Application::VERSION,
            'phpVersion' => phpversion(),
        ]);
    }

    return redirect('/dashboard');
});

Route::get('dashboard', function () {
    $videos = Video::where('user_id', Auth::id())->latest('created_at')->get();

    return Inertia::render('Dashboard', [
        'videos' => $videos,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // TODO: Routes disabled due to the app being a demo project for now.
    // Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('video', VideoController::class)->middleware(['auth', 'verified']);
    Route::middleware('throttle:10,1')->post('video/{video}/cover-image-upload', [VideoController::class, 'handleCoverImageUpload'])->name('video.cover-image-upload');

    Route::resource('person', PersonController::class)->middleware(['auth', 'verified']);

    Route::middleware('throttle:10,1')->post('person/{person}/avatar-upload', [PersonController::class, 'handleAvatarUpload'])
        ->name('avatar-upload');
});

Route::get('cookies', function () {
    return Inertia::render('Cookies');
})->name('cookies');

Route::get('privacy', function () {
    return Inertia::render('Privacy');
})->name('privacy');

Route::get('terms', function () {
    return Inertia::render('Terms');
})->name('terms');
