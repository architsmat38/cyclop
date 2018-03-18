<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'EventsController@getEventsAndFilters');
Route::get('/event/{event_id}', 'EventsController@getEventPageDetails');
Route::get('/create_event', 'EventsController@createEventPage');
Route::get('/create_event/{event_id}', 'EventsController@editEventPage');

Route::post('/filter_events', 'EventsController@getFilteredEvents');
Route::post('/create_events/preauth', 'AuthController@checkIfAuthCookieIsValid');
Route::post('/create_events/auth', 'AuthController@checkCreateEventAuth');
Route::post('/create_events/create', 'EventsController@createEvent');
