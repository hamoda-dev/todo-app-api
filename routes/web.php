<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::controller(DashboardController::class)
    ->prefix('dashboard')
    ->as('dashboard.')
    ->middleware(['auth', 'is-admin'])
    ->group(function () {
        Route::get('/', 'show')->name('show');
        Route::post('regenrate-key', 'regenrateKey')->name('regenrate-key');
    });

require __DIR__.'/auth.php';
