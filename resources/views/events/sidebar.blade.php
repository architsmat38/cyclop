<div class="ui vertical menu event_filter_sidebar">
	<!-- City -->
	<div class="item">
	    <div class="header">City</div>
	    <div class="menu">
	    	@foreach($filters['cities'] as $city)
		        <div class="ui checkbox item event_filter" data-filter_type="city" data-city_id={{$city->city_id}}>
				    <input type="checkbox" name="example">
				    <label>{{$city->name}}</label>
				</div>
			@endforeach
	    </div>
  	</div>

	<!-- Event Type -->
	<div class="item">
	    <div class="header">Type</div>
	    <div class="menu">
	    	@foreach($filters['event_types'] as $event_type)
		        <div class="ui checkbox item event_filter" data-filter_type="event_type" data-event_type_id={{$event_type->event_type_id}}>
				    <input type="checkbox" name="example">
				    <label>{{$event_type->event_type}}</label>
				</div>
			@endforeach
	    </div>
  	</div>

  	<!-- Distance -->
  	<div class="item">
	    <div class="header">Total KM</div>
	    <div class="menu">
	        <div class="ui checkbox item event_filter" data-filter_type="distance" data-start_distance=0 data-end_distance=100>
			    <input type="checkbox" name="example">
			    <label>Upto 100</label>
			</div>
			<div class="ui checkbox item event_filter" data-filter_type="distance" data-start_distance=101 data-end_distance=250>
			    <input type="checkbox" name="example">
			    <label>100-250</label>
			</div>
			<div class="ui checkbox item event_filter" data-filter_type="distance" data-start_distance=251 data-end_distance=500>
			    <input type="checkbox" name="example">
			    <label>250-500</label>
			</div>
			<div class="ui checkbox item event_filter" data-filter_type="distance" data-start_distance=501 data-end_distance=-1>
			    <input type="checkbox" name="example">
			    <label>500+</label>
			</div>
	    </div>
  	</div>

  	<!-- Cost -->
  	<div class="item">
	    <div class="header">Cost</div>
	    <div class="menu">
	        <div class="ui checkbox item event_filter" data-filter_type="cost" data-start_cost=0 data-end_cost=5000>
			    <input type="checkbox" name="example">
			    <label>Under 5k</label>
			</div>
			<div class="ui checkbox item event_filter" data-filter_type="cost" data-start_cost=5001 data-end_cost=10000>
			    <input type="checkbox" name="example">
			    <label>5k - 10k</label>
			</div>
			<div class="ui checkbox item event_filter" data-filter_type="cost" data-start_cost=10001 data-end_cost=25000>
			    <input type="checkbox" name="example">
			    <label>10k - 25k</label>
			</div>
			<div class="ui checkbox item event_filter" data-filter_type="cost" data-start_cost=25001 data-end_cost=-1>
			    <input type="checkbox" name="example">
			    <label>25k+</label>
			</div>
	    </div>
  	</div>

  	<!-- Terrain -->
  	<div class="item">
	    <div class="header">Terrain</div>
	    <div class="menu">
	    	@foreach($filters['terrains'] as $terrain)
		        <div class="ui checkbox item event_filter" data-filter_type="terrain" data-terrain_id={{$terrain->terrain_id}}>
				    <input type="checkbox" name="example">
				    <label>{{$terrain->name}}</label>
				</div>
			@endforeach
	    </div>
  	</div>

  	<!-- Can I get cycle -->
  	<div class="item">
	    <div class="header">Can I get cycle from organisers?</div>
	    <div class="menu">
	        <div class="ui checkbox item event_filter" data-filter_type="cycle_available">
			    <input type="checkbox" name="example">
			    <label>Yes</label>
			</div>
	    </div>
  	</div>
</div>