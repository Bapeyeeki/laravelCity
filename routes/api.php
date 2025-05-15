<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\GameResultController;

Route::get('/cities', [CityController::class, 'index']);
Route::get('/cities/{name}', [CityController::class, 'show']);

Route::post('/game-results', [GameResultController::class, 'store']);