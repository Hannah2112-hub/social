<?php

use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;

Route::view('/','welcome');

Route::post('/statuses', [StatusController::class, 'store'])->name('statuses.store')->middleware('auth');

Route::get('login', function(){
    return "Vista login";
})->name('login');
