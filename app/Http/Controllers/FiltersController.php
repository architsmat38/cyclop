<?php

namespace App\Http\Controllers;
use App\City;
use App\EventType;
use App\Terrain;

class FiltersController extends Controller {

    public static function getAllFilters() {
    	return [
            'cities' => City::all(),
            'event_types' => EventType::all(),
            'terrains' => Terrain::all()
        ];
    }
}
