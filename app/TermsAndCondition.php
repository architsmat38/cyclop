<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TermsAndCondition extends Model {

    /**
     * Fetch terms and conditions related to an event
     */
    public static function getTnCByEventId($event_id) {
    	$sql = 'SELECT * FROM terms_and_conditions
    			WHERE event_id = ?
    			AND is_active = 1';
    	$tnc_details = DB::select($sql, [$event_id]);

    	return $tnc_details;
    }
}
