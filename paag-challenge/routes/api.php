<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;

Route::controller(RequestController::class)->group(function () { // To work according vegeta script.
    Route::get('users/{username}/requests', 'index')->name('user.request.index');
    Route::post('request', 'store')->name('request.store');
});
