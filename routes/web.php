<?php

use App\Http\Controllers\API\WhmcsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group([], function () {
    // Route::get('login-whmcs', [WhmcsController::class, 'whmcsConn'])->name('login-whmcs');
    Route::get('view-client', [WhmcsController::class, 'index'])->name('view-client');
    Route::get('add-client', [WhmcsController::class, 'create'])->name('add-client');
    Route::post('create-client', [WhmcsController::class, 'store'])->name('create-client');
});
