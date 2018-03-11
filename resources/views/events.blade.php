@extends('layouts.app')
<!-- Javascript -->
@section('javascript')
	<script type="text/javascript" src="/js/app/event.js"></script>
@endsection

<link rel='stylesheet' href='/css/app/event.css' />

@section('content')
	<div class='ui container mtop20'>
		<div class='ui grid'>
			<div class='four wide column'>
				@include('events.sidebar')
			</div>
			<div class='twelve wide column'>
				<div class='ui segment'>
					<h2 class='ui header'>
						CYCLING EVENTS
						<p class='sub header'>{{$quote}}</p>
					</h2>
				</div>
				<div id='cycling_events_section'>
					@include('events.main')
				</div>
				<div class='ui segment' id='initial_loading'>
					<div class='ui active inverted dimmer'>
						<div class='ui medium text loader'>Unleashing Happiness..!!</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection