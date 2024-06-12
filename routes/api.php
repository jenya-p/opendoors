<?php

use Illuminate\Support\Facades\Route;

Route::post('/yookassa', [\App\Http\Controllers\Profile\PaymentController::class, 'yookassaWebHook'])
    ->name('yookassaWebHook');
