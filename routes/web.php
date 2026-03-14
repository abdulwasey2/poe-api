<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PoeModelController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/models', [PoeModelController::class, 'index']);
