<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\TermsAndCondition;
use App\City;
use App\EventType;
use App\Terrain;
use App\Partner;

class EventsController extends Controller {

    public function formatEventInfo($event) {
        $event->duration = round((strtotime($event->end_date) - strtotime($event->start_date)) / (60 * 60 * 24)) + 1;
        if (date('y', strtotime($event->start_date)) == date('y', strtotime($event->end_date))) {
            $event->start_date = date('d M', strtotime($event->start_date));
        } else {
            $event->start_date = date('d M \'y', strtotime($event->start_date));
        }

        $event->end_date = date('d M \'y', strtotime($event->end_date));
        $event->distance_in_km = round($event->distance / 1000);

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

    /**
     * Open up the page for the event creation
     */
    public function createEventPage() {
        $cities = City::all();
        $event_types = EventType::all();
        $terrains = Terrain::all();
        $partners = Partner::all();

        return view('event_creation', [
            'cities' => $cities,
            'event_types' => $event_types,
            'terrains' => $terrains,
            'partners' => $partners
        ]);
    }

    /**
     * Open up the page to edit the existing event
     */
    public function editEventPage(Request $request, $event_id) {
        $cities = City::all();
        $event_types = EventType::all();
        $terrains = Terrain::all();
        $partners = Partner::all();
        $event_details = Event::getEventDetailsById($event_id);

        return view('event_creation', [
            'cities' => $cities,
            'event_types' => $event_types,
            'terrains' => $terrains,
            'partners' => $partners,
            'event_details' => $event_details
        ]);
    }

    /**
     * Validate event details
     */
    public function validateEventDetails(Request $request) {
        $event_details = $request->all();

        if (empty($event_details['title']) || empty($event_details['subtitle']) ||
            empty($event_details['description']) || empty($event_details['start_date']) ||
            empty($event_details['end_date']) || empty($event_details['distance']) ||
            !isset($event_details['amount']) ||
            empty($event_details['start_city_id']) || empty($event_details['end_city_id']) ||
            empty($event_details['terrain_id']) || empty($event_details['event_type_id']) ||
            !isset($event_details['cycle_available']) || empty($event_details['address']) ||
            empty($event_details['latitude']) || empty($event_details['longitude'])) {
            return false;
        }

        if (!is_numeric($event_details['distance']) || !is_numeric($event_details['amount']) ||
            !is_numeric($event_details['start_city_id']) || !is_numeric($event_details['end_city_id']) ||
            !is_numeric($event_details['terrain_id']) || !is_numeric($event_details['event_type_id']) ||
            !is_numeric($event_details['latitude']) || !is_numeric($event_details['longitude'])) {
            return false;
        }

        return true;
    }

    /**
     * Create event
     */
    public function createEvent(Request $request) {
        $response = array('success' => false, 'message' => '');

        if ($this->validateEventDetails($request)) {
            // Create event once the details are all valid
            $details = $request->all();
            $event_obj = Event::initWithData($details);
            $event_id = $event_obj->create();

            if ($event_id) {
                // Upload Images
                move_uploaded_file($details['thumbnail-image']->path(), public_path() . '/images/events/event_' . $event_id . '.png'); 
                move_uploaded_file($details['cover-image']->path(), public_path() . '/images/event_page/event_page_' . $event_id . '.png');

                $response['success'] = true;
                $response['message'] = "Event has been created successfully.";
                $response['event_id'] = $event_id;
            }
        } else {
            $response['message'] = 'Event details are not valid.';
        }

        return response()->json($response);
    }
}
