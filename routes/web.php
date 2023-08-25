<?php

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

Route::get('/', function () {
    return redirect('/transforms');
});

Auth::routes(['register' => false]);

Route::resource('/transforms', App\Http\Controllers\TransformController::class)->middleware('auth');
Route::get('/transforms/{id}/{type}/config', [App\Http\Controllers\TransformController::class, 'transform'])->name('transforms.config')->middleware('auth');
Route::resource('/providers', App\Http\Controllers\ProviderController::class)->middleware('auth');
Route::get('/providers/{id}/secret', [App\Http\Controllers\ProviderController::class, 'secret'])->middleware('auth')->name('providers.secret');
Route::patch('/providers/{id}/secret', [App\Http\Controllers\ProviderController::class, 'generateSecret'])->middleware('auth')->name('providers.secret.generate');
Route::get('/change-password', [App\Http\Controllers\AuthController::class, 'password'])->middleware('auth')->name('auth.change-password');
Route::patch('/change-password', [App\Http\Controllers\AuthController::class, 'changePassword'])->middleware('auth')->name('auth.change-password');
