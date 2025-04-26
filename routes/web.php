<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SizeController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function(){

    Route::get('size', [SizeController::class, 'index'])->name('size');
    Route::get('get-sizes', [SizeController::class, 'getSizes'])->name('getsizes');
    ROute::post('store-size', [SizeController::class, 'store'])->name('storesize');
    ROute::post('update-size', [SizeController::class, 'update'])->name('updatesize');
    ROute::post('delete-size', [SizeController::class, 'delete'])->name('deletesize');
});
