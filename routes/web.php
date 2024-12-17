<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::post('/menus/store', [MenuController::class, 'store'])->name('menus.store');
Route::get('/', [MenuController::class, 'index'])->name('menus.index');
