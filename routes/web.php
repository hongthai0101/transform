<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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

Route::get('/', function () {
    return redirect('/transforms');
});

Auth::routes(['register' => DB::table('users')->count() === 0]);

Route::resource('/transforms', App\Http\Controllers\TransformController::class)->middleware('auth');
Route::get('/transforms/{id}/{type}/config', [App\Http\Controllers\TransformController::class, 'transform'])->name('transforms.config')->middleware('auth');
Route::resource('/providers', App\Http\Controllers\ProviderController::class)->middleware('auth');
