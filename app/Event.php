<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model {
    private $data;

    public function __construct(int $event_id = 0) {
        if ($event_id) {
            $event_details = self::getEventDetailsById($event_id);
            $this->data = json_decode(json_encode($event_details), true);
        } else {
            $this->data = array();
        }
    }

    public function  __get($name) {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return null;
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    /**
     * Fetch event details by event id
     */
    public static function getEventDetailsById($event_id) {
        $sql = self::fetchAllEventDetailsSql();
        $sql .= ' AND events.`event_id` = ?';

        $event_details = DB::select($sql, [$event_id])[0];
        return $event_details;
    }

    /**
     * Create event object with the event details information
     */
    public static function initWithData(array $event_details) {
        $event_obj = new self();

        $event_obj->title = $event_details['title'];
        $event_obj->subtitle = $event_details['subtitle'];
        $event_obj->description = $event_details['description'];
        $event_obj->start_date = $event_details['start_date'];
        $event_obj->end_date = $event_details['end_date'];
        $event_obj->distance = $event_details['distance'];
        $event_obj->amount = $event_details['amount'];
        $event_obj->start_city_id = $event_details['start_city_id'];
        $event_obj->end_city_id = $event_details['end_city_id'];
        $event_obj->address = $event_details['address'];
        $event_obj->latitude = $event_details['latitude'];
        $event_obj->longitude = $event_details['longitude'];
        $event_obj->terrain_id = $event_details['terrain_id'];
        $event_obj->event_type_id = $event_details['event_type_id'];
        $event_obj->cycle_available = $event_details['cycle_available'];

        return $event_obj;
    }

    /**
     * Create a new event
     */
    public function createEvent() {
        $event_id = self::insertGetId(
            array(
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'description' => $this->description,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'distance' => $this->distance,
                'amount' => $this->amount,
                'start_city_id' => $this->start_city_id,
                'end_city_id' => $this->end_city_id,
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'terrain_id' => $this->terrain_id,
                'event_type_id' => $this->event_type_id,
                'cycle_available' => $this->cycle_available,
                'is_active' => 1
            )
        );

        return $event_id;
    }

    /**
     * Update existing event
     */
    public function updateEvent($event_details) {
        $event_id = $this->event_id;
        $new_event_obj = self::initWithData($event_details);

        // Check which parameter needs to be updated
        $updates = array();
        // Title
        if ($this->title != $new_event_obj->title) {
            $updates['title'] = $new_event_obj->title;
        }

        // Subtitle
        if ($this->subtitle != $new_event_obj->subtitle) {
            $updates['subtitle'] = $new_event_obj->subtitle;
        }

        // Description
        if ($this->description != $new_event_obj->description) {
            $updates['description'] = $new_event_obj->description;
        }

        // Start date
        if ($this->start_date != $new_event_obj->start_date) {
            $updates['start_date'] = $new_event_obj->start_date;
        }

        // End date
        if ($this->end_date != $new_event_obj->end_date) {
            $updates['end_date'] = $new_event_obj->end_date;
        }

        // Distance
        if ($this->distance != $new_event_obj->distance) {
            $updates['distance'] = $new_event_obj->distance;
        }

        // Amount
        if ($this->amount != $new_event_obj->amount) {
            $updates['amount'] = $new_event_obj->amount;
        }

        // Start city id
        if ($this->start_city_id != $new_event_obj->start_city_id) {
            $updates['start_city_id'] = $new_event_obj->start_city_id;
        }

        // End city id
        if ($this->end_city_id != $new_event_obj->end_city_id) {
            $updates['end_city_id'] = $new_event_obj->end_city_id;
        }

        // Address
        if ($this->address != $new_event_obj->address) {
            $updates['address'] = $new_event_obj->address;
        }

        // Latitude
        if ($this->latitude != $new_event_obj->latitude) {
            $updates['latitude'] = $new_event_obj->latitude;
        }

        // Longitude
        if ($this->longitude != $new_event_obj->longitude) {
            $updates['longitude'] = $new_event_obj->longitude;
        }

        // Terrain id
        if ($this->terrain_id != $new_event_obj->terrain_id) {
            $updates['terrain_id'] = $new_event_obj->terrain_id;
        }

        // Event type id
        if ($this->event_type_id != $new_event_obj->event_type_id) {
            $updates['event_type_id'] = $new_event_obj->event_type_id;
        }

        // Cycle Available
        if ($this->cycle_available != $new_event_obj->cycle_available) {
            $updates['cycle_available'] = $new_event_obj->cycle_available;
        }

        $update_status = self::where('event_id', $event_id)->update($updates);

        return ($update_status ? $event_id : 0);
    }

    /**
     * Fetch all event details related sql
     * This sql query fetches all event related information as well
     */
	public static function fetchAllEventDetailsSql() {
		$sql = 'SELECT events.*,
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
				WHERE events.`is_active` = 1';

		return $sql;
	}

    /**
     * Fetch all event details
     */
    public static function fetchAllEventDetails() {
    	$sql = self::fetchAllEventDetailsSql();
    	$sql .= ' ORDER BY start_date ASC ';

    	$events = DB::select($sql);
    	return $events;
    }

    /**
     * Fetch event details by applying the provided filters
     */
    public static function fetchFilteredEventDetails($filters) {
    	$sql = self::fetchAllEventDetailsSql();

    	foreach ($filters as $filter_type => $filter_values) {
    		$table_name = '';
    		switch ($filter_type) {
    			case 'city':
    				$sql .= ' AND (c1.`city_id` IN ('.implode(',', $filter_values).')
    						  OR c2.`city_id` IN ('.implode(',', $filter_values).')) ';
    				break;

    			case 'event_type':
    				$table_name = empty($table_name) ? 'event_types' : $table_name;
    			case 'terrain':
    				$table_name = empty($table_name) ? 'terrains' : $table_name;
    				$sql .= ' AND '. $table_name .'.'. $filter_type .'_id IN ('.implode(',', $filter_values).') ';
    				break;
    			
    			case 'distance':
    			case 'cost':
    				$filter_type = $filter_type == 'cost' ? 'amount' : $filter_type;
    				$total_filters = count($filter_values);
    				$sql .= $total_filters > 0 ? ' AND (' : '';

    				$index = 0;
    				foreach ($filter_values as $filter_value) {
    					$start_end_values = explode('-', $filter_value);

    					if (count($start_end_values) == 2) {
    						$start_end_values[0] = $filter_type == 'distance' ? $start_end_values[0] * 1000 : $start_end_values[0];
    						$start_end_values[1] = ($filter_type == 'distance' && $start_end_values[1] != 'inf') ?
    												$start_end_values[1] * 1000 : $start_end_values[1];

    						$sql .= $index > 0 ? ' OR ' : '';
    						$sql .= '(events.'.$filter_type.' >= ' . $start_end_values[0] . ' ';
    						if ($start_end_values[1] != 'inf') {
    							$sql .= ' AND events.'.$filter_type.' <= ' . $start_end_values[1] . ' ';
    						}
    						$sql .= ')';
							$index++;
    					}
    				}

    				$sql .= $total_filters > 0 ? ')' : '';
    				break;

    			case 'cycle_available':
    				$filter_value = $filter_values[0] ?? -1;
    				if ($filter_value != -1) {
    					$sql .= ' AND '. $filter_type .' = '. $filter_value .' ';
    				}
    				break;
    		}
    	}

    	$sql .= ' ORDER BY start_date ASC ';
    	$events = DB::select($sql);
    	return $events;
    }
}
