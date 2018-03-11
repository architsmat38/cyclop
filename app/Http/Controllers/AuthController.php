<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller {

    public static function checkCreateEventAuth(Request $request) {
    	$auth_pass = config('auth.create_event_pass');
    	$request_pass = $request->all()['pass'] ?? '';
    	$response = array('success' => false, 'message' => 'Entered password is incorrect. Please try again.');

    	if ($request_pass == $auth_pass) {
    		$response['success'] = true;
    		$response['message'] = 'Validation is successful';
    	}
    	return response()->json($response);
    }
}
