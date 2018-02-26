<div class='ui segment'>
	<h2 class='ui header'>
		CYCLING EVENTS
		<p class='sub header'>{{$quote}}</p>
	</h2>
</div>

@if(count($events) > 0)
	@foreach($events as $event)
		<div class='ui segment'>
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
								<p class='sub header'>{{$event->description}}</p>
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
						<div class='four wide column'>
							<p>{{$event->currency}} {{$event->amount}}</p>
						</div>

						<!-- Distance -->
						<div class='four wide column'>
							<p>{{$event->distance_in_km}} KM</p>
						</div>

						<!-- Terrain -->
						<div class='four wide column'>
							<p>{{$event->terrain_name}}</p>
						</div>

						<!-- Duration -->
						<div class='four wide column'>
							<p>{{$event->duration}} @if($event->duration > 1) days @else day @endif</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
@else
	<div class='ui segment'>
		<p class='text-center ptop20 pbot20'>No Upcoming Events Found. Stay Tuned!!</p>
	</div>
@endif