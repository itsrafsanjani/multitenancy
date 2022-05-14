<?php

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/settings', function () {
    $tenant = tenant('id', 'data');
    return $tenant;
    return view('settings', compact('tenant'));
})->middleware(['auth'])->name('settings');

Route::post('/settings', function () {
    return view('settings');
})->middleware(['auth'])->name('settings.store');

require __DIR__ . '/auth.php';
