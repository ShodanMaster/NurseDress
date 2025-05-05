<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Master\BinController;
use App\Http\Controllers\Master\ColorController;
use App\Http\Controllers\Master\DesignController;
use App\Http\Controllers\Master\EmployeeController;
use App\Http\Controllers\Master\ItemController;
use App\Http\Controllers\Master\LocationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\SizeController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\RejectionController;
use App\Http\Controllers\Transaction\QcController;
use App\Http\Controllers\Transaction\StorageController;
use App\Http\Controllers\Transaction\GrnController;

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/loging-in', [LoginController::class, 'logingIn'])->name('loggingin');

Route::middleware('auth')->group(function () {

    Route::get('logging-out', [LoginController::class, 'loggingOut'])->name('loggingout');

    Route::get('/', function () {
        return view('welcome');
    })->name('dashboard');

    Route::prefix('master')->name('master.')->group(function(){

        Route::get('size', [SizeController::class, 'index'])->name('size');
        Route::get('get-sizes', [SizeController::class, 'getSizes'])->name('getsizes');
        Route::post('store-size', [SizeController::class, 'store'])->name('storesize');
        Route::post('update-size', [SizeController::class, 'update'])->name('updatesize');
        Route::post('delete-size', [SizeController::class, 'delete'])->name('deletesize');
        Route::get('sizes-excel-export', [SizeController::class, 'sizeExcelExport'])->name('sizeexcelexport');

        Route::get('color', [ColorController::class, 'index'])->name('color');
        Route::get('get-colors', [ColorController::class, 'getColors'])->name('getcolors');
        Route::post('store-color', [ColorController::class, 'store'])->name('storecolor');
        Route::post('update-color', [ColorController::class, 'update'])->name('updatecolor');
        Route::post('delete-color', [ColorController::class, 'delete'])->name('deletecolor');
        Route::get('colors-excel-export', [ColorController::class, 'colorExcelExport'])->name('colorexcelexport');

        Route::get('design', [DesignController::class, 'index'])->name('design');
        Route::get('get-designs', [DesignController::class, 'getDesigns'])->name('getdesigns');
        Route::post('store-design', [DesignController::class, 'store'])->name('storedesign');
        Route::post('update-design', [DesignController::class, 'update'])->name('updatedesign');
        Route::post('delete-design', [DesignController::class, 'delete'])->name('deletedesign');
        Route::get('designs-excel-export', [DesignController::class, 'designExcelExport'])->name('designexcelexport');

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

        Route::get('employee', [EmployeeController::class, 'index'])->name('employee');
        Route::get('get-employees', [EmployeeController::class, 'getEmployees'])->name('getemployees');
        Route::post('store-employee', [EmployeeController::class, 'store'])->name('storeemployee');
        Route::post('update-employee', [EmployeeController::class, 'update'])->name('updateemployee');
        Route::post('delete-employee', [EmployeeController::class, 'delete'])->name('deleteemployee');

        Route::get('user', [UserController::class, 'index'])->name('user');
        Route::get('get-users', [UserController::class, 'getUsers'])->name('getusers');
        Route::post('store-user', [UserController::class, 'store'])->name('storeuser');
        Route::post('update-user', [UserController::class, 'update'])->name('updateuser');
        Route::post('delete-user', [UserController::class, 'delete'])->name('deleteuser');
    });

    Route::prefix('transaction')->name('transaction.')->group(function(){

        Route::get('grn', [GrnController::class, 'index'])->name('grn');
        Route::post('grn-store', [GrnController::class, 'store'])->name('grnstore');
        Route::get('grn-edit', [GrnController::class, 'edit'])->name('grnedit');
        Route::get('fetch-grn', [GrnController::class, 'fetchGrn'])->name('fetchgrn');
        Route::post('grn-update', [GrnController::class, 'update'])->name('grnupdate');

        Route::get('quality-check', [QcController::class, 'index'])->name('qualitycheck');
        Route::post('qc-store', [QcController::class, 'store'])->name('qcstore');
        Route::get('fetch-grnitem', [QcController::class, 'fetchItem'])->name('fetchitem');

        Route::get('storage', [StorageController::class, 'index'])->name('storage');
        Route::post('storage-scan', [StorageController::class, 'store'])->name('storagescan');

        Route::get('rejection', [RejectionController::class, 'index'])->name('rejection');
        Route::get('fetch-bin', [StorageController::class, 'fetchBin'])->name('fetchbin');
        // Route::post('qc-store', [StorageController::class, 'store'])->name('qcstore');

    });

    Route::prefix('test')->name('test.')->group(function(){
        Route::get('test', function () {
            return view('welcome');
        })->name('welcome');
    });

});
