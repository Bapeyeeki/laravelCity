<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\City;
use App\Http\Controllers\CityController;
use App\Http\Controllers\GameResultController;

Route::get('/', function () {
    return view('app');  // używaj 'app' zamiast 'welcome'
});

Route::get('/', function () {
    return Inertia::render('HelloWorld');  
});

// Trasa do pobrania wszystkich miast
Route::get('/cities', [CityController::class, 'index']);

// Trasa do pobrania konkretnego miasta po nazwie
Route::get('/cities/{name}', [CityController::class, 'show']);