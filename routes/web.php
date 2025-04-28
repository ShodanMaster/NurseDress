<?php

use App\Http\Controllers\BinController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SizeController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function(){

    Route::get('size', [SizeController::class, 'index'])->name('size');
    Route::get('get-sizes', [SizeController::class, 'getSizes'])->name('getsizes');
    Route::post('store-size', [SizeController::class, 'store'])->name('storesize');
    Route::post('update-size', [SizeController::class, 'update'])->name('updatesize');
    Route::post('delete-size', [SizeController::class, 'delete'])->name('deletesize');

    Route::get('color', [ColorController::class, 'index'])->name('color');
    Route::get('get-colors', [ColorController::class, 'getColors'])->name('getcolors');
    Route::post('store-color', [ColorController::class, 'store'])->name('storecolor');
    Route::post('update-color', [ColorController::class, 'update'])->name('updatecolor');
    Route::post('delete-color', [ColorController::class, 'delete'])->name('deletecolor');

    Route::get('design', [DesignController::class, 'index'])->name('design');
    Route::get('get-designs', [DesignController::class, 'getDesigns'])->name('getdesigns');
    Route::post('store-design', [DesignController::class, 'store'])->name('storedesign');
    Route::post('update-design', [DesignController::class, 'update'])->name('updatedesign');
    Route::post('delete-design', [DesignController::class, 'delete'])->name('deletedesign');

    Route::get('location', [LocationController::class, 'index'])->name('location');
    Route::get('get-locations', [LocationController::class, 'getLocations'])->name('getlocations');
    Route::post('store-location', [LocationController::class, 'store'])->name('storelocation');
    Route::post('update-location', [LocationController::class, 'update'])->name('updatelocation');
    Route::post('delete-location', [LocationController::class, 'delete'])->name('deletelocation');

    Route::get('bin', [BinController::class, 'index'])->name('bin');
    Route::get('get-bins', [BinController::class, 'getBins'])->name('getbins');
    Route::post('store-bin', [BinController::class, 'store'])->name('storebin');
    Route::post('update-bin', [BinController::class, 'update'])->name('updatebin');
    Route::post('delete-bin', [BinController::class, 'delete'])->name('deletebin');

    Route::get('item', [ItemController::class, 'index'])->name('item');
    Route::get('get-items', [ItemController::class, 'getItems'])->name('getitems');
    Route::post('store-item', [ItemController::class, 'store'])->name('storeitem');
    Route::post('update-item', [ItemController::class, 'update'])->name('updateitem');
    Route::post('delete-item', [ItemController::class, 'delete'])->name('deleteitem');
});
