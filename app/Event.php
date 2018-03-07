<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model {

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

    /**
     * Fetch event details by event id
     */
    public static function getEventDetailsById($event_id) {
        $sql = self::fetchAllEventDetailsSql();
        $sql .= ' AND events.`event_id` = ?';

        $event_details = DB::select($sql, [$event_id])[0];
        return $event_details;
    }
}
