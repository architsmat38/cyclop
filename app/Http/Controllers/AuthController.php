<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller {

	const CREATE_EVENT_AUTH_COOKEY_KEY = 'ae_cyclop';

	/**
	 * Check if auth is still valid from the cookie
	 */
	public static function checkIfAuthCookieIsValid() {
		$auth_pass = config('auth.create_event_pass.pass');
		$auth_salt = config('auth.create_event_pass.salt');

		$valid_cookie_val = md5($auth_pass . $auth_salt);
		$request_auth_cookie = $_COOKIE[self::CREATE_EVENT_AUTH_COOKEY_KEY] ?? '';

		$response = array(
			'success' => $request_auth_cookie == $valid_cookie_val
		);
		return response()->json($response);
	}

	/**
	 * Check is password entered is valid
	 * Once the auth is verified, a cookie is set so that user does not have to re-login
	 */
    public static function checkCreateEventAuth(Request $request) {
    	$auth_pass = config('auth.create_event_pass.pass');
    	$auth_salt = config('auth.create_event_pass.salt');
    	$request_pass = $request->all()['pass'] ?? '';
    	$response = array('success' => false, 'message' => 'Entered password is incorrect. Please try again.');

    	if ($request_pass == $auth_pass) {
    		$response['success'] = true;
    		$response['message'] = 'Validation is successful';

    		$cookie_name = self::CREATE_EVENT_AUTH_COOKEY_KEY;
			$cookie_value = md5($auth_pass . $auth_salt);
			setcookie($cookie_name, $cookie_value, time() + (24 * 60 * 60), "/");	// 1 day
    	}

    	return response()->json($response);
    }
}
