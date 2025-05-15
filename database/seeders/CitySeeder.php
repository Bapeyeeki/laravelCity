<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class CitySeeder extends Seeder
{
    public function run()
    {
        $csv = Reader::createFromPath(database_path('data/PL_czysty.csv'), 'r');
        $csv->setHeaderOffset(0); // assuming first row = headers

        foreach ($csv as $record) {
            City::create([
                'city' => $record['miasto'],  // Dostosuj do nazwy nagłówka w CSV
                'lat' => $record['lat'],
                'lng' => $record['lng'],
                'population' => $record['population']  // Zmiana nazwy pola w modelu
            ]);
        }
    }
}