<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameResult;

class GameResultController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'playerName' => 'required|string|max:255',
            'foundCities' => 'required|integer|min:0',
            'totalPopulation' => 'required|integer|min:0',
            'score' => 'required|integer|min:0',
        ]);

        GameResult::create($validated);

        return response()->json(['message' => 'Wynik zapisany'], 201);
    }
}