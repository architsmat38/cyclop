<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\TermsAndCondition;

class EventsController extends Controller {

    public function formatEventInfo($event) {
        $event->duration = round((strtotime($event->end_date) - strtotime($event->start_date)) / (60 * 60 * 24)) + 1;
        if (date('y', strtotime($event->start_date)) == date('y', strtotime($event->end_date))) {
            $event->start_date = date('d M', strtotime($event->start_date));
        } else {
            $event->start_date = date('d M \'y', strtotime($event->start_date));
        }

        $event->end_date = date('d M \'y', strtotime($event->end_date));
        $event->distance_in_km = $event->distance / 1000;

        return $event;
    }

    public function formatEventsData($events) {
        // Format events data
        foreach ($events as &$event) {
            $event = self::formatEventInfo($event);
        }

        return $events;
    }

    /**
     * Get all events
     */
    public function getEvents() {
    	$events = Event::fetchAllEventDetails();
        $events = self::formatEventsData($events);
    	return $events;
    }

    /**
     * Get all events and the filters that can be applied on it
     */
    public function getEventsAndFilters() {
    	$events = $this->getEvents();
    	$quote = QuotesController::getRandomQuote();
        $filters = FiltersController::getAllFilters();

    	return view('events', ['events' => $events, 'quote' => $quote, 'filters' => $filters]);
    }

    /**
     * Get events view after applying the filters
     */
    public function getFilteredEvents(Request $request) {
        $events = Event::fetchFilteredEventDetails($request->all());
        $events = self::formatEventsData($events);
        $filtered_events_html = view('events.main', ['events' => $events])->render();
        return response()->json(array('success' => true, 'events_html' => $filtered_events_html));
    }

    /**
     * Get the event details based on the passed event id
     */
    public function getEventPageDetails(Request $request, $event_id) {
        $event_details = Event::getEventDetailsById($event_id);
        $event_details = self::formatEventInfo($event_details);

        $tnc_details = TermsAndCondition::getTnCByEventId($event_id);
        return view('event_page', ['event_details' => $event_details, 'tnc_details' => $tnc_details]);
    }
}
