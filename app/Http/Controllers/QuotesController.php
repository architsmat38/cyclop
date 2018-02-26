<?php

namespace App\Http\Controllers;

class QuotesController extends Controller {

    public static function getAllQuotes() {
    	return [
            '“Ride as much or as little, as long or as short as you feel. But ride” – Eddy Merckx',
            '“Don’t buy upgrades, ride up grades” – Eddy Merckx',
            '“When my legs hurt, I say: “Shut up legs! Do what I tell you to do!” – Jens Voigt',
            '“It never gets easier, you just get faster” – Greg LeMond',
            '“Nothing compares to the simple pleasure of riding a bike” – John F Kennedy',
            '“A bicycle ride around the world begins with a single pedal stroke” – Scott Stoll',
            '“The race is won by the rider who can suffer the most” – Eddy Merckx',
            '“When it’s hurting you, that’s when you can make a difference” – Eddy Merckx',
            '“As long as I breathe, I attack” – Bernard Hinault',
            '“Ride a bike. Ride a bike. Ride a bike” – Fausto Coppi',
            '“Good morale in cycling comes from good legs” Sean Yates',
            '“It is the unknown around the corner that turns my wheels” – Heinz Stucke'
        ];
    }

    public static function getRandomQuote() {
    	$all_quotes = self::getAllQuotes();
        $random_index = rand(0, count($all_quotes) - 1);

    	return $all_quotes[$random_index];
    }
}
