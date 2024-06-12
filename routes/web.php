<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Profile\InfoController;
use \App\Http\Controllers\Profile\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'home']);

Route::get('/dashboard', function(){
    return redirect()->route('admin.user.index');
})->name('dashboard');

Route::get('/{slug}', [\App\Http\Controllers\HomeController::class, 'content'])
    ->whereIn('slug', ['about', 'agreement']);

Route::get('/prices', [\App\Http\Controllers\HomeController::class, 'prices'])->name('prices');
Route::get('/purchase', [PaymentController::class, 'create'])->name('purchase');

Route::get('/backfeed', [\App\Http\Controllers\BackfeedController::class, 'create'])->name('backfeed.create');
Route::post('/backfeed', [\App\Http\Controllers\BackfeedController::class, 'store'])->name('backfeed.store');

Route::get('/attachment/{attachment}/download', [\App\Http\Controllers\AttachmentController::class, 'download'])->name('attachment.download');
Route::get('/attachment/{attachment}/thumb', [\App\Http\Controllers\AttachmentController::class, 'thumb'])->name('attachment.thumb');

Route::middleware('auth')->group(function () {

    Route::resource('attachment', \App\Http\Controllers\AttachmentController::class)->only(['index', 'store', 'update', 'destroy']);


});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'can:admin']], function () {

    Route::any('/', function(){
        return redirect()->route('admin.user.index');
    })->name('dashboard');

    Route::get('user/suggest', [\App\Http\Controllers\Admin\UserController::class, 'suggest'])->name('user.suggest');
    Route::resource('user', \App\Http\Controllers\Admin\UserController::class)
        ->except(['destroy', 'show']);
    Route::resource('admin', \App\Http\Controllers\Admin\AdminController::class)
        ->except(['show']);
    Route::resource('university-user', \App\Http\Controllers\Admin\UniversityUserController::class)
        ->except(['show']);
    Route::resource('student', \App\Http\Controllers\Admin\StudentController::class)
        ->except(['show']);


    Route::resource('content', \App\Http\Controllers\Admin\ContentController::class)
        ->only(['index', 'edit', 'update']);

    Route::resource('edu-level', \App\Http\Controllers\Admin\EduLevelController::class)->except(['show']);
    Route::resource('university', \App\Http\Controllers\Admin\UniversityController::class)->except(['show']);
    Route::resource('track', \App\Http\Controllers\Admin\TrackController::class)->except(['show']);
    Route::resource('profile', \App\Http\Controllers\Admin\ProfileController::class)->except(['show']);
    Route::resource('stage', \App\Http\Controllers\Admin\StageController::class)->except(['show', 'create', 'store']);

    Route::resource('backfeed', \App\Http\Controllers\Admin\BackfeedController::class)
        ->except(['show', 'create', 'store']);


});

require __DIR__.'/auth.php';

