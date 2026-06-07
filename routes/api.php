<?php

use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;


Route::post('/quotes', [QuoteController::class, 'store']);
Route::get('/quotes', [QuoteController::class, 'index']);