<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;

class EventsController extends Controller {

    public function getEvents() {
    	$events = Event::fetchEventDetails();

    	// Format events data
    	foreach ($events as &$event) {
    		$event->duration = round((strtotime($event->end_date) - strtotime($event->start_date)) / (60 * 60 * 24)) + 1;
    		if (date('y', strtotime($event->start_date)) == date('y', strtotime($event->end_date))) {
    			$event->start_date = date('d M', strtotime($event->start_date));
    		} else {
    			$event->start_date = date('d M \'y', strtotime($event->start_date));
    		}

    		$event->end_date = date('d M \'y', strtotime($event->end_date));
    		$event->distance_in_km = $event->distance / 1000;
    	}

    	return $events;
    }

    public function getEventsAndFilters() {
    	$events = $this->getEvents();
    	$quote = QuotesController::getRandomQuote();
        $filters = FiltersController::getAllFilters();

    	return view('events', ['events' => $events, 'quote' => $quote, 'filters' => $filters]);
    }
}
