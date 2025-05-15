<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Zwraca wszystkie miasta jako JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Pobiera wszystkie rekordy z tabeli cities
        $cities = City::all();

        // Zwraca je jako JSON
        return response()->json($cities);
    }

    /**
     * Zwraca dane miasta na podstawie nazwy (case insensitive).
     *
     * @param  string  $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($cityName)
{
    // Normalizuj nazwę miasta np. na małe litery
    $cityName = strtolower($cityName);

    // Szukaj miasta w bazie (przykład)
    $city = City::whereRaw('LOWER(city) = ?', [$cityName])->first();

    if (!$city) {
        return response()->json(['message' => 'Miasto nie znalezione'], 404);
    }

    return response()->json($city);
}
}