<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameResult extends Model
{
    protected $fillable = ['playerName', 'foundCities', 'totalPopulation', 'score'];
}