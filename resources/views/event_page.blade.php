@extends('layouts.app')
<link rel='stylesheet' href='/css/app/event_page.css' />

@section('content')
<!-- Event Cover -->
<img class='event_cover_image' src='{{ asset("images/event_page/event_page_{$event_details->event_id}.png") }}'>

<!-- Header Section -->
<div class='ui top attached segment mtop0'>
	<div class='ui container'>
		<div class='ui grid'>
			<!-- Date of event -->
			<div class='two wide column'>
				<div class='event_date'>
					{{$event_details->start_date}} - {{$event_details->end_date}}
				</div>
			</div>

			<!-- Event Name -->
			<div class='ten wide column'>
				<div class='ui large header mbot10'>
					{{$event_details->title}}
				</div>
				<div class='event_brief_details'>
					<!-- City -->
					<span>
						@if($event_details->start_city_id == $event_details->end_city_id)
							{{$event_details->start_city_name}}
						@else
							{{$event_details->start_city_name}} - {{$event_details->end_city_name}}
						@endif
					</span>
					<span class='event_brief_separator'>|</span>
					<!-- Distance -->
					<span>
						{{$event_details->distance_in_km}} KM
					</span>
					<span class='event_brief_separator'>|</span>
					<!-- Terrain -->
					<span>
						{{$event_details->terrain_name}}
					</span>
					<span class='event_brief_separator'>|</span>
					<!-- Duration -->
					<span>
						{{$event_details->duration}} @if($event_details->duration > 1) days @else day @endif
					</span>
				</div>
			</div>

			<!-- Register -->
			<div class='four wide column text-center'>
				<div class='ui teal button register_button'>
					Register Now
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Details Section -->
<div class='ui container'>
	<div class='ui grid mtop20'>
		<div class='twelve wide column'>
			<!-- Overview -->
			<div class='ui segment'>
				<div class='ui small header event_page_header pbot5'>
					OVERVIEW
				</div>
				<div class='event_page_description'>
					{!! $event_details->description !!}
				</div>
			</div>

			<!-- Terms and Conditions -->
			@if(count($tnc_details) > 0)
				<div class='ui segment'>
					<div class='ui small header event_page_header pbot5'>
						TERMS & CONDITIONS
					</div>
					<div class='event_page_tnc'>
						<ul>
						@foreach($tnc_details as $tnc)
							<li>{!! $tnc->description !!}</li>
						@endforeach
						</ul>
					</div>
				</div>
			@endif
		</div>

		<div class='four wide column'>
			<div class='ui segment'>
				<div class='ui small header event_page_header pbot5'>
					VENUE DETAILS
				</div>
				<div id='venue_map'></div>
				<div class='event_page_address mtop20'>
					{{$event_details->address}}
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Load Google Maps -->
<script>
  function initMap() {
    var uluru = {lat: {{$event_details->latitude}}, lng: {{$event_details->longitude}}};
    var map = new google.maps.Map(document.getElementById('venue_map'), {
      zoom: 15,
      center: uluru
    });
    var marker = new google.maps.Marker({
      position: uluru,
      map: map
    });
  }
</script>
<script async defer
	src="https://maps.googleapis.com/maps/api/js?key={{config('google-maps.api_key')}}&callback=initMap">
</script>
@endsection