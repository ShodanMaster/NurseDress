<?php

use App\Http\Controllers\ColorController;
use App\Http\Controllers\DesignController;
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

    Route::get('color', [ColorController::class, 'index'])->name('color');
    Route::get('get-colors', [ColorController::class, 'getColors'])->name('getcolors');
    ROute::post('store-color', [ColorController::class, 'store'])->name('storecolor');
    ROute::post('update-color', [ColorController::class, 'update'])->name('updatecolor');
    ROute::post('delete-color', [ColorController::class, 'delete'])->name('deletecolor');

    Route::get('design', [DesignController::class, 'index'])->name('design');
    Route::get('get-designs', [DesignController::class, 'getDesigns'])->name('getdesigns');
    ROute::post('store-design', [DesignController::class, 'store'])->name('storedesign');
    ROute::post('update-design', [DesignController::class, 'update'])->name('updatedesign');
    ROute::post('delete-design', [DesignController::class, 'delete'])->name('deletedesign');
});
