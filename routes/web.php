<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SizeController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function(){

    Route::get('size', [SizeController::class, 'index'])->name('size');
});