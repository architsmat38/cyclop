String.prototype.isEmpty = function() {
	return (this.length === 0 || !this.trim());
};

Date.prototype.formatDate = function() {
	return this.getFullYear() + '-' + ((this.getMonth() + 1) < 10 ? '0'+(this.getMonth() + 1) : (this.getMonth() + 1)) + '-' + this.getDate();
}

/**
 * Bind click event for the auth modal
 * Includes submission of auth
 */
function bindClickEventsForAuthModal() {
	$('#submit-auth').off('click').on('click', function() {
		var auth_pass = $("input[name=auth-password]").val();

		if (!auth_pass.isEmpty()) {
			$.ajax({
		        type: "POST",
		        url: "/create_events/auth",
		        data: {
		        	'pass' : auth_pass
		        },
		        beforeSend: function() {
		        	$('#auth_error').hide();
		        },
		        success: function(response) {
		            if (response.success) {
		            	$('#auth-modal').modal('hide');
		            	$('#create-event-div').show();
		            } else {
		            	$('#auth_error').html(response.message);
		            	$('#auth_error').show();
		            }
		        }
		    });

		} else {
			$('#auth_error').html("Please enter password and then submit again");
			$('#auth_error').show();
		}
	});
}

/**
 * Check if auth entered is still valid
 */
function checkIfAuthIsStillValid() {
	$.ajax({
        type: "POST",
        url: "/create_events/preauth",
        success: function(response) {
            if (response.success) {
            	$('#create-event-div').show();
            } else {
            	// Open auth modal
			  	$('#auth-modal').modal({
			  		onVisible: function() {
			  			bindClickEventsForAuthModal();
			  		},
			  		onApprove: function() {
			  			return false;
			  		}
			  	}).modal('setting', 'closable', false).modal('show');
            }
        }
    });
}

/**
 * Create/Edit event based on the mentioned details
 */
function createEditEvent() {

	var event_id = 0;
	if ($('#edit_event_label').length != 0) {
		// Case of edit event
		event_id = $('#edit_event_label').data('event_id');
	}

 	// Basic information
 	var title = $("input[name=event-title]").val();
 	var subtitle = $("input[name=event-subtitle]").val();
 	var description = $("textarea[name=event-desc]").val();

 	var start_date = $("#event-start-date").calendar('get date');
 	start_date = start_date.formatDate();

 	var end_date = $("#event-end-date").calendar('get date');
 	end_date = end_date.formatDate();
 	var amount = $("input[name=event-amount]").val();

 	var start_city = $("#event-start-city").dropdown('get value');
 	var end_city = $("#event-end-city").dropdown('get value');
 	var distance = $("input[name=event-distance]").val();

 	// Extra information
 	var terrain = $("#event-terrain").dropdown('get value');
 	var event_type = $("#event-type").dropdown('get value');
 	var is_cycle_available = $('#event-cycle-available').checkbox('is checked');

 	var address = $("textarea[name=event-venue-address]").val();
 	var latitude = $("input[name=event-latitude]").val();
 	var longitude = $("input[name=event-longitude]").val();

 	// Form data
 	var form_data = new FormData();
 	form_data.append('event_id', event_id);
 	form_data.append('title', title);
 	form_data.append('subtitle', subtitle);
 	form_data.append('description', description);
 	form_data.append('start_date', start_date);
 	form_data.append('end_date', end_date);
 	form_data.append('start_city_id', start_city);
 	form_data.append('end_city_id', end_city);
 	form_data.append('distance', distance);
 	form_data.append('amount', amount);
 	form_data.append('terrain_id', terrain);
 	form_data.append('event_type_id', event_type);
 	form_data.append('cycle_available', is_cycle_available ? 1 : 0);
 	form_data.append('address', address);
 	form_data.append('latitude', latitude);
 	form_data.append('longitude', longitude);

 	form_data.append('thumbnail-image', $('#tb-file')[0].files[0]);
 	form_data.append('cover-image', $('#cv-file')[0].files[0]);
 	
 	// Create Event
 	$.ajax({
        type: "POST",
        url: "/create_events/create",
        data: form_data,
        processData: false,
        contentType: false,
        beforeSend: function() {
        	$('#submit-event').addClass('loading');
        	$('#submit-event').css('pointer-events', 'none');
        },
        success: function(response) {
            alert(response.message);

            if (response.success) {
            	// Re-direct to the new event page
            	window.location.href = '/event/' + response.event_id;
            }
        },
        complete: function() {
        	$('#submit-event').removeClass('loading');
        	$('#submit-event').css('pointer-events', '');
        }
    });
}

/**
 * Pre-fill form in case of edit event
 */
function prefillForEditEvent() {
	var event_div = $('#edit_event_details');

	$("input[name=event-title]").val(event_div.data('title'));
	$("input[name=event-subtitle]").val(event_div.data('subtitle'));
	$("textarea[name=event-desc]").val(event_div.data('description'));

	$("#event-start-date").calendar('set date', (new Date(event_div.data('start_date'))));
	$("#event-end-date").calendar('set date', (new Date(event_div.data('end_date'))));
	$("input[name=event-amount]").val(event_div.data('amount'));

	$("#event-start-city").dropdown('set selected', event_div.data('start_city_id'));
	$("#event-end-city").dropdown('set selected', event_div.data('end_city_id'));
	$("input[name=event-distance]").val(event_div.data('distance'));

	$("#event-terrain").dropdown('set selected', event_div.data('terrain_id'));
 	$("#event-type").dropdown('set selected', event_div.data('event_type_id'));

 	$("textarea[name=event-venue-address]").val(event_div.data('address'));
 	$("input[name=event-latitude]").val(event_div.data('latitude'));
 	$("input[name=event-longitude]").val(event_div.data('longitude'));

 	var cycle_available = event_div.data('cycle_available');
 	if (cycle_available == 1) {
 		$('#event-cycle-available').checkbox('set checked');
 	} else {
 		$('#event-cycle-available').checkbox('set unchecked');
 	}
}

/**
 * Load and bind events once the form is loaded properly
 */
$(document).ready(function() {
	// Check if auth is still valid
  	checkIfAuthIsStillValid();

	$('.ui.dropdown').dropdown();
	$('.ui.checkbox').checkbox();

	// Calendar events
	$('#event-start-date').calendar({
		type: 'date',
		endCalendar: $('#event-end-date'),
		popupOptions: {
			position: 'bottom left',
			lastResort: 'bottom left',
			prefer: false,
			hideOnScroll: false
		}
	});

	$('#event-end-date').calendar({
		type: 'date',
		startCalendar: $('#event-start-date'),
		popupOptions: {
			position: 'bottom left',
			lastResort: 'bottom left',
			prefer: false,
			hideOnScroll: false
		}
	});

	// Form validation
	$('#create-event-form').form({
	    fields: {
	      'event-title' : 'empty',
	      'event-subtitle' : 'empty',
	      'event-desc' : 'empty',
	      'event-start-date' : 'empty',
	      'event-end-date' : 'empty',
	      'event-start-city' : 'empty',
	      'event-end-city' : 'empty',
	      'event-distance' : 'empty',
	      'event-amount' : 'empty',
	      'event-terrain' : 'empty',
	      'event-type' : 'empty',
	      'event-venue-address' : 'empty',
	      'event-latitude' : 'empty',
	      'event-longitude' : 'empty'
	    },
	    onSuccess: function(event, fields) {
	    	createEditEvent();
	    }
  	});

  	// Create new event redirection page
  	$('#create_new_event').off('click').on('click', function() {
  		window.location.href = '/create_event';
  	});

  	// Pre-fill fields in case of edit event
  	prefillForEditEvent();
});