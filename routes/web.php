<?php

use App\Http\Controllers\Profile\InfoController;
use App\Http\Controllers\Profile\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/set-locale/{locale}', function ($locale) {
    Session::put('locale', $locale);
    if (session()->has('locale_changed')) {
        return redirect('/')->with('locale_changed', $locale);
    } else {
        return redirect()->back()->with('locale_changed', $locale);
    }
})->whereIn('locale', ['en', 'ru'])
    ->name('set-locale');

Route::group(['as' => 'public.', 'middleware' => []], function () {
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'home'])->name('home');
    Route::get('/about', [\App\Http\Controllers\HomeController::class, 'about'])->name('about');
    Route::get('/rules', [\App\Http\Controllers\HomeController::class, 'rules'])->name('rules');
    Route::get('/olympiad', [\App\Http\Controllers\HomeController::class, 'olympiad'])->name('olympiad');
    Route::get('/subject/{profile}', [\App\Http\Controllers\HomeController::class, 'profile'])->name('profile.show');
    Route::get('/news', [\App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{news}', [\App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

});


Route::get('/dashboard', function () {
    return redirect()->route('admin.user.index');
})->name('dashboard');

Route::get('/{slug}', [\App\Http\Controllers\HomeController::class, 'content'])
    ->whereIn('slug', ['about', 'agreement']);

Route::get('/prices', [\App\Http\Controllers\HomeController::class, 'prices'])->name('prices');


// Route::get('/backfeed', [\App\Http\Controllers\BackfeedController::class, 'create'])->name('backfeed.create');
// Route::post('/backfeed', [\App\Http\Controllers\BackfeedController::class, 'store'])->name('backfeed.store');

Route::get('/attachment/{attachment}/download', [\App\Http\Controllers\AttachmentController::class, 'download'])->name('attachment.download');
Route::get('/attachment/{attachment}/thumb', [\App\Http\Controllers\AttachmentController::class, 'thumb'])->name('attachment.thumb');

Route::middleware('auth')->group(function () {
    Route::resource('attachment', \App\Http\Controllers\AttachmentController::class)->only(['index', 'store', 'update', 'destroy']);
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'can:admin']], function () {

    Route::any('/', function () {
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


    Route::resource('news', \App\Http\Controllers\Admin\Content\NewsController::class)->except(['show']);
    Route::resource('widget', \App\Http\Controllers\Admin\Content\WidgetController::class)->except(['show', 'create', 'store', 'delete']);
    Route::resource('profile-file', \App\Http\Controllers\Admin\Content\ProfileFileController::class)->except(['show']);
    Route::resource('schedule', \App\Http\Controllers\Admin\Content\ScheduleController::class)->except(['show']);
    Route::resource('faq', \App\Http\Controllers\Admin\Content\FaqController::class)->except(['show']);


    Route::resource('edu-level', \App\Http\Controllers\Admin\EduLevelController::class)->except(['show']);
    Route::resource('university', \App\Http\Controllers\Admin\UniversityController::class)->except(['show']);
    Route::resource('track', \App\Http\Controllers\Admin\TrackController::class)->except(['show']);
    Route::resource('profile', \App\Http\Controllers\Admin\ProfileController::class)->except(['show']);
    Route::resource('stage', \App\Http\Controllers\Admin\StageController::class)->except(['show', 'create', 'store']);

    Route::resource('quiz', \App\Http\Controllers\Admin\Quiz\QuizController::class)->except([]);
    Route::resource('quiz-question', \App\Http\Controllers\Admin\Quiz\QuestionController::class)
        ->parameters(['quiz-question' => 'question'])
        ->except([]);


    Route::get('quiz-probe/show', [\App\Http\Controllers\Admin\Quiz\ProbeController::class, 'show'])->name('quiz-probe.show');
    Route::get('quiz-probe/{question}', [\App\Http\Controllers\Admin\Quiz\ProbeController::class, 'probe'])->name('quiz-probe.probe');
    Route::post('quiz-probe', [\App\Http\Controllers\Admin\Quiz\ProbeController::class, 'check'])->name('quiz-probe.check');


//    Route::resource('backfeed', \App\Http\Controllers\Admin\BackfeedController::class)
//        ->except(['show', 'create', 'store']);


});

require __DIR__ . '/auth.php';

