String.prototype.isEmpty = function() {
	return (this.length === 0 || !this.trim());
};

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

$(document).ready(function() {
	$('.ui.dropdown').dropdown();
	$('.ui.checkbox').checkbox();

	var today = new Date();
	$('#event-start-date').calendar({
		type: 'date',
		minDate: today,
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
		minDate: today,
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
	      'event-terrain' : 'empty',
	      'event-type' : 'empty',
	      'event-venue-address' : 'empty',
	      'event-latitude' : 'empty',
	      'event-longitude' : 'empty'
	    }
  	});

  	// Open auth modal
  	$('#auth-modal').modal({
  		onVisible: function() {
  			bindClickEventsForAuthModal();
  		},
  		onApprove: function() {
  			return false;
  		}
  	}).modal('setting', 'closable', false).modal('show');
});