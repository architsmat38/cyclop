@extends('layouts.app')
<!-- Javascript -->
@section('javascript')
	<script type="text/javascript" src="/js/app/event_creation.js"></script>
@endsection

<link rel='stylesheet' href='/css/app/event_creation.css' />

@section('content')
	<!-- Event creation div -->
	<div class='ui segment' id='create-event-div'>
		<div class='ui container'>
			@if (empty($event_details))
				<!-- Create event -->
				<div class='ui large dividing header mbot10'>
					Create Event
				</div>
			@else
				<!-- Edit event -->
				<div class='ui large dividing header mbot10'>
					Edit Event
				</div>

				<div class='ui large blue label'>
					<i class='ui bicycle icon'></i>
					Event id: {{ $event_details->event_id }}
				</div>
				<br>

				<div id='edit_event_details' class='display-none'
				@foreach($event_details as $key => $value)
					data-{{ $key }}="{{ $value }}"
				@endforeach
				></div>
			@endif
			<br>

			<!-- Event creation form -->
			<form class='ui form' id='create-event-form' onsubmit="event.preventDefault();">
				<div class='ui dividing header'>Basic Information</div>

				<!-- Basic Info -->
				<div class='required nine wide field'>
					<label>Title</label>
					<input type='text' placeholder='Event Name' name='event-title'>
				</div>

				<div class='required field'>
					<label>Subtitle</label>
					<input type='text' placeholder='Event Subtitle' name='event-subtitle'>
				</div>

				<div class='required field'>
					<label>Description</label>
					<textarea placeholder='Event description in detail' name='event-desc'></textarea>
				</div>

				<!-- Dates -->
				<div class='two fields'>
					<div class='required four wide field'>
						<label>Start Date</label>
						<div class='ui calendar' id='event-start-date'>
							<div class='ui input left icon'>
								<i class='calendar alternate outline icon'></i>
								<input type='text' placeholder='Event Start Date' name='event-start-date' readonly>
							</div>
						</div>
					</div>

					<div class='one wide field'></div>

					<div class='required four wide field'>
						<label>End Date</label>
						<div class='ui calendar' id='event-end-date'>
							<div class='ui input left icon'>
								<i class='calendar alternate outline icon'></i>
								<input type='text' placeholder='Event End Date' name='event-end-date' readonly>
							</div>
						</div>
					</div>

					<div class='one wide field'></div>

					<!-- Amount -->
					<div class='required four wide field'>
						<label>Amount (INR)</label>
						<input type='number' placeholder='Amount' name='event-amount' value='0'>
					</div>
				</div>

				<div class='three fields'>
					<!-- City -->
					<div class='required four wide field'>
						<label>Start City</label>
						<select class="ui fluid dropdown" name='event-start-city' id='event-start-city'>
							<option value="">Select starting point</option>
    						@foreach($cities as $city)
    							<option value="{{$city->city_id}}">{{$city->name}}</option>
    						@endforeach
						</select>
					</div>

					<div class='one wide field'></div>

					<div class='required four wide field'>
						<label>End City</label>
						<select class="ui fluid dropdown" name='event-end-city' id='event-end-city'>
							<option value="">Select destination point</option>
    						@foreach($cities as $city)
    							<option value="{{$city->city_id}}">{{$city->name}}</option>
    						@endforeach
						</select>
					</div>

					<div class='one wide field'></div>

					<!-- Distance -->
					<div class='required four wide field'>
						<label>Distance (in meters)</label>
						<input type='number' placeholder='Distance' name='event-distance'>
					</div>
				</div>

				<!-- Event Images -->
				<div class='two fields'>
					<div class='required four wide field'>
						<label>Thumbnail Image</label>
						<div class='ui left icon input'>
							<input type="file" name="tb-file" id="tb-file" accept=".png" required/>
							<i class="upload icon"></i>
						</div>
					</div>

					<div class='one wide field'></div>

					<div class='required four wide field'>
						<label>Cover Image</label>
						<div class='ui left icon input'>
							<input type="file" name="cv-file" id="cv-file" accept=".png" required/>
							<i class="upload icon"></i>
						</div>
					</div>
				</div>
				<br>

				<div class='ui dividing header'>Extra Information</div>

				<div class='three fields'>
					<!-- Terrain -->
					<div class='required four wide field'>
						<label>Terrain Type</label>
						<select class="ui fluid dropdown" name='event-terrain' id='event-terrain'>
							<option value="">Select terrain type</option>
							@foreach($terrains as $terrain)
    							<option value="{{$terrain->terrain_id}}">{{$terrain->name}}</option>
    						@endforeach
						</select>
					</div>

					<div class='one wide field'></div>

					<!-- Event type -->
					<div class='required four wide field'>
						<label>Event Type</label>
						<select class="ui fluid dropdown" name='event-type' id='event-type'>
							<option value="">Select event type</option>
							@foreach($event_types as $event_type)
    							<option value="{{$event_type->event_type_id}}">{{$event_type->event_type}}</option>
    						@endforeach
						</select>
					</div>

					<div class='one wide field'></div>

					<div class="field">
						<label>Can individual get cycle from organizers?</label>
					    <div class="ui checkbox mtop10" id='event-cycle-available'>
					    	<input type="checkbox" tabindex="0" class="hidden" name='event-cycle-available'>
					      	<label>Yes, cycle is available</label>
					    </div>
				  	</div>
				</div>


				<div class='required field'>
					<label>Venue Address</label>
					<textarea rows="2" placeholder='Enter the complete venue address' name='event-venue-address'></textarea>
				</div>

				<div class='two fields'>
					<div class='required four wide field'>
						<label>Latitude</label>
						<input type='number' placeholder='Venue latitude' name='event-latitude'>
					</div>

					<div class='one wide field'></div>

					<div class='required four wide field'>
						<label>Longitude</label>
						<input type='number' placeholder='Venue longitude' name='event-longitude'>
					</div>
				</div>
				<div class='note'>
					*In order to fetch latitude and logitude for a place, please follow steps as mentioned <a href='https://www.wikihow.com/Get-Latitude-and-Longitude-from-Google-Maps' target='_blank'>here</a>.
				</div>
				<br>

				<div class='ui teal submit button create_event_button style-button' id='submit-event'>Create Event</div>
				<div class="ui error message"></div>
			</form>
		</div>
	</div>

	<!-- Auth Modal -->
	<div class='ui mini modal' id='auth-modal'>
		<div class="header">Auth</div>
	  	<div class="content">
	  		<div class='ui tiny error message' id='auth_error'>
	  			<li>Entered password is incorrect</li>
	  		</div>

	    	<div class='ui form'>
	    		<div class='required field'>
	    			<label>Password</label>
	    			<input type='password' placeholder='Enter a valid password' name='auth-password'>
	    		</div>
	    	</div>
	  	</div>
	  	<div class="actions">
	    	<div class="ui green approve button" id='submit-auth'>Submit</div>
	  	</div>
	</div>
@endsection