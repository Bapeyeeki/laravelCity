<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    // Funkcja do usuwania polskich znaków
    private function removePolishChars($string)
    {
        $polish = ['ą','ć','ę','ł','ń','ó','ś','ź','ż','Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż'];
        $english = ['a','c','e','l','n','o','s','z','z','A','C','E','L','N','O','S','Z','Z'];
        return str_replace($polish, $english, $string);
    }

    public function index()
    {
        $cities = City::all();
        return response()->json($cities);
    }

    public function show($cityName)
    {
        $cityNameNormalized = strtolower($this->removePolishChars($cityName));

        // Pobieramy wszystkie miasta i szukamy po znormalizowanej nazwie
        $city = City::all()->first(function ($city) use ($cityNameNormalized) {
            $dbCityNormalized = strtolower($this->removePolishChars($city->city));
            return $dbCityNormalized === $cityNameNormalized;
        });

        if (!$city) {
            return response()->json(['message' => 'Miasto nie znalezione'], 404);
        }

        return response()->json($city);
    }
}