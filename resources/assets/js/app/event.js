String.prototype.isEmpty = function() {
	return (this.length === 0 || !this.trim());
};

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

	var web_url = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port;
	web_url += (event_filters_str.length > 0) ? "?" + event_filters_str : '';
	window.history.pushState("", "", web_url);
}

// Remove key from array
function remove(array, element) {
    const index = array.indexOf(element);

    if (index !== -1) {
        array.splice(index, 1);
    }
}

function bindClickEvents() {
	// Re-direction to event page
	$('.event_snippet').off('click').on('click', function() {
		var event_id = $(this).data('event_id');
		window.location.href = "/event/" + event_id;
	});
}

// Apply filters to event results
function applyFilters(element) {
	var checkbox_element = $(element).parents('.event_filter')[0];
	var filter_type = $(checkbox_element).data('filter_type');
	var is_checked = $(checkbox_element).checkbox('is checked');

	switch(filter_type) {
		case 'city':
			var city_id = $(checkbox_element).data('city_id').toString();
			if (is_checked) {
				window.event_filters.city.push(city_id);
			} else {
				remove(window.event_filters.city, city_id);
			}
			break;

		case 'event_type':
			var event_type_id = $(checkbox_element).data('event_type_id').toString();
			if (is_checked) {
				window.event_filters.event_type.push(event_type_id);
			} else {
				remove(window.event_filters.event_type, event_type_id);
			}
			break;

		case 'distance':
			var start_distance = $(checkbox_element).data('start_distance').toString();
			var end_distance = $(checkbox_element).data('end_distance').toString();
			var distance_key = start_distance + '-' + end_distance;

			if (is_checked) {
				window.event_filters.distance.push(distance_key);
			} else {
				remove(window.event_filters.distance, distance_key);
			}
			break;

		case 'cost':
			var start_cost = $(checkbox_element).data('start_cost').toString();
			var end_cost = $(checkbox_element).data('end_cost').toString();
			var cost_key = start_cost + '-' + end_cost;

			if (is_checked) {
				window.event_filters.cost.push(cost_key);	
			} else {
				remove(window.event_filters.cost, cost_key);
			}
			break;

		case 'terrain':
			var terrain_id = $(checkbox_element).data('terrain_id').toString();
			if (is_checked) {
				window.event_filters.terrain.push(terrain_id);
			} else {
				remove(window.event_filters.terrain, terrain_id);
			}
			break;

		case 'cycle_available':
			if (is_checked) {
				window.event_filters.cycle_available.push("1");
			} else {
				remove(window.event_filters.cycle_available, "1");
			}
			break;
	}

	changeWebUrl();
}

// Prefill window object with the filters present in the url
function prefillEventFilters() {
	var event_filters_str = window.location.search.replace('?&', '');
	if (!event_filters_str.isEmpty()) {
		var all_filters = event_filters_str.split('&');

		for (var i = 0; i < all_filters.length; i++) {
			var filter_category_with_values = all_filters[i].split('=');

			if (filter_category_with_values.length == 2) {
				var filter_category = filter_category_with_values[0];
				var filter_values = filter_category_with_values[1].split(',');
				window.event_filters[filter_category] = filter_values;

				for (var filter_row_index = 0; filter_row_index < filter_values.length; filter_row_index++) {
					var filter_row_val = filter_values[filter_row_index];

					// Check the filters which are provided in url
					switch (filter_category) {
						case 'city':
						case 'event_type':
						case 'terrain':
							var filter_elements = $('.event_filter_' + filter_category);
							for (var filter_element_index = 0; filter_element_index < filter_elements.length; filter_element_index++) {
								var filter_row_element = $(filter_elements[filter_element_index]);
								if (filter_row_element.data(filter_category + '_id') == filter_row_val) {
									filter_row_element.checkbox('set checked');
								}
							};
							break;

						case 'distance':
						case 'cost':
							var start_end_value = filter_row_val.split('-');
							var filter_elements = $('.event_filter_' + filter_category);
							for (var filter_element_index = 0; filter_element_index < filter_elements.length; filter_element_index++) {
								var filter_row_element = $(filter_elements[filter_element_index]);
								if (filter_row_element.data('start_' + filter_category) == start_end_value[0] && filter_row_element.data('end_' + filter_category) == start_end_value[1]) {
									filter_row_element.checkbox('set checked');
								}
							};
							break;

						case 'cycle_available':
							if (filter_row_val == '1') {
								$($('.event_filter_' + filter_category)[0]).checkbox('set checked');
							}
							break;
					}
				}
			}
		};
	}
}

// Check if any filter is applied
function checkIfFiltersApplied() {
	var event_filters = window.event_filters;
	var event_filters_keys = Object.keys(event_filters);

	for (var i = 0; i < event_filters_keys.length; i++) {
		if (event_filters[event_filters_keys[i]].length > 0) {
			return true;
		}
	}

	return false;
}

// Set default filters in window object
function setDefaultFilters() {
	window.event_filters = {
		'city': [],
		'event_type': [],
		'distance': [],
		'cost': [],
		'terrain': [],
		'cycle_available': []
	};
}

// Render filtered events
function renderFilteredEvents() {
	$.ajax({
        type: "POST",
        url: "/filter_events",
        data: window.event_filters,
        beforeSend: function() {
        	$('#initial_loading').show();
        	$('#cycling_events_section').hide();
        },
        success: function(response) {
            if (response.success) {
            	$('#cycling_events_section').html(response.events_html);
            	bindClickEvents();
            } else {
            	// TODO :: ERROR HANDLING
            }
        },
        complete: function() {
        	$('#initial_loading').hide();
        	$('#cycling_events_section').show();
        }
    });
}

// Bind events once the page is loaded
$(document).ready(function() {
	// Default filters
	setDefaultFilters();

	// Pre-fill event filters
	prefillEventFilters();

    $('.ui.checkbox').checkbox({
    	onChange: function() {
    		applyFilters(this);
    		renderFilteredEvents();	// Render filtered event results
    	}
    });

    // Reset filters
    $('.event_filter_remove_all').off('click').on('click', function() {
    	setDefaultFilters();
    	changeWebUrl();
    	$('.ui.checkbox').checkbox('set unchecked');
    	renderFilteredEvents();
    });

    // Render filtered event results
    if (checkIfFiltersApplied()) {
    	renderFilteredEvents();

    } else {
    	$('#initial_loading').hide();
    	$('#cycling_events_section').show();
    	bindClickEvents();
    }
});
