@if(count($events) > 0)
	@foreach($events as $event)
		<div class='ui segment event_snippet' data-event_id={{$event->event_id}}>
			<div class='ui grid'>
				<!-- Event Image -->
				<div class='four wide column'>
					<img class='event_card_image' src='{{ asset("images/events/event_{$event->event_id}.png") }}'>
				</div>

				<!-- Event Description -->
				<div class='twelve wide column'>
					<div class='ui grid'>
						<div class='eleven wide column'>
							<h3 class='ui header'>
								{{$event->title}}
								<p class='sub header'>{{$event->subtitle}}</p>
							</h3>
						</div>
						<!-- Duration -->
						<div class='five wide column text-right'>
							<p class='ui large sub header red'>{{$event->start_date}} - {{$event->end_date}}</p>
						</div>
					</div>

					<!-- Location -->
					<p class='mbot0 mtop5'>
						@if($event->start_city_id == $event->end_city_id)
							{{$event->start_city_name}}
						@else
							{{$event->start_city_name}} - {{$event->end_city_name}}
						@endif
					</p>

					<div class='ui grid mtop0'>
						<!-- Amount -->
						<div class='three wide column'>
							<p>{{$event->currency}} {{$event->amount}}</p>
						</div>

						<!-- Distance -->
						<div class='three wide column'>
							<p>{{$event->distance_in_km}} KM</p>
						</div>

						<!-- Terrain -->
						<div class='three wide column'>
							<p>{{$event->terrain_name}}</p>
						</div>

						<!-- Duration -->
						<div class='three wide column'>
							<p>{{$event->duration}} @if($event->duration > 1) days @else day @endif</p>
						</div>

						<!-- Register Button -->
						<div class='four wide column'>
							<div class='ui tiny teal button register_button style-button'>
								Register
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	@endforeach
@else
	<div class='ui segment'>
		<p class='text-center ptop30 pbot30'>No Events Found ...!!</p>
	</div>
@endif