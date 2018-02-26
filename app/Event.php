<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model {

    public static function fetchEventDetails() {
    	$events = DB::select(
    		'SELECT events.*,
			terrains.`name` AS terrain_name, partners.`name` AS partner_name,
			partners.`description` AS partner_description, partners.`url` AS partner_url,
			event_types.`event_type`,
			c1.`name` AS start_city_name, c2.`name` AS end_city_name
			FROM events
			INNER JOIN terrains
			ON events.`terrain_id` = terrains.`terrain_id`
			INNER JOIN cities c1
			ON events.`start_city_id` = c1.`city_id`
			INNER JOIN `cities` c2
			ON events.`end_city_id` = c2.`city_id`
			LEFT JOIN partners
			ON events.`partner_id` = partners.`partner_id`
			LEFT JOIN event_types
			ON events.`event_type_id` = event_types.`event_type_id`
			WHERE events.`is_active` = 1
			ORDER BY start_date ASC'
    	);

    	return $events;
    }
}
