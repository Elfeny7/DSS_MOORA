<?php

use App\Http\Controllers\MooraController;
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
    return redirect()->route('input');
});

Route::get('/input', function () {
    return view('input');
})->name('input');

Route::post('/table', [MooraController::class, 'table'])->name('table');
Route::post('/hasil', [MooraController::class, 'hitung'])->name('hasil');
