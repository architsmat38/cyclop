// Change web url and update it with new filters
function changeWebUrl() {
	var filter_categories = Object.keys(window.event_filters);
	var total_filter_categories = filter_categories.length;
	var event_filters_str = '';
	for (var i = 0; i < total_filter_categories; i++) {
		var category_values = window.event_filters[filter_categories[i]];

		for (var index = 0; index < category_values.length; index++) {
			event_filters_str += (index == 0) ? '&' + filter_categories[i] + '=' : '';
			event_filters_str += category_values[index] + ((index != category_values.length - 1) ? ',' : '');
		};
	};

	var web_url = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "?" + event_filters_str;
	window.history.pushState("", "", web_url);
}

function remove(array, element) {
    const index = array.indexOf(element);

    if (index !== -1) {
        array.splice(index, 1);
    }
}

// Apply filters to event results
function applyFilters(element) {
	var checkbox_element = $(element).parents('.event_filter')[0];
	var filter_type = $(checkbox_element).data('filter_type');
	var is_checked = $(checkbox_element).checkbox('is checked');

	switch(filter_type) {
		case 'city':
			var city_id = $(checkbox_element).data('city_id');
			if (is_checked) {
				window.event_filters.city.push(city_id);
			} else {
				remove(window.event_filters.city, city_id);
			}
			break;

		case 'event_type':
			var event_type_id = $(checkbox_element).data('event_type_id');
			if (is_checked) {
				window.event_filters.event_type.push(event_type_id);
			} else {
				remove(window.event_filters.event_type, event_type_id);
			}
			break;

		case 'distance':
			break;

		case 'cost':
			break;

		case 'terrain':
			var terrain_id = $(checkbox_element).data('terrain_id');
			if (is_checked) {
				window.event_filters.terrain.push(terrain_id);
			} else {
				remove(window.event_filters.terrain, terrain_id);
			}
			break;

		case 'cycle_available':
			if (is_checked) {
				window.event_filters.cycle_available.push(1);
			} else {
				remove(window.event_filters.cycle_available, 1);
			}
			break;
	}

	changeWebUrl();
}

// Bind events once the page is loaded
$(document).ready(function() {
	// Default filters
	window.event_filters = {
		'city': [],
		'event_type': [],
		'distance': [],
		'cost': [],
		'terrain': [],
		'cycle_available': []
	};

    $('.ui.checkbox').checkbox({
    	onChange: function() {
    		applyFilters(this);
    	}
    });
});