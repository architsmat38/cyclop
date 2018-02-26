@extends('layouts.app')

@section('content')
	<div class='ui container mtop20'>
		<div class='ui grid'>
			<div class='four wide column'>
				@include('events.sidebar')
			</div>
			<div class='twelve wide column'>
				@include('events.main')
			</div>
		</div>
	</div>
@endsection