jQuery(document).ready(function ($) {
            

        $(document).on('click', '.kt-review-convert', function(e) {
	        e.preventDefault();
	        $('.redux-main #redux_ajax_overlay').fadeIn();
		    var data = {
				action: 'kt_review_convert',
			};

			$.post(ajaxurl, data, function(response) {
	           	if( jQuery.trim(response.value) == 'success' ) {
	           		$('.redux-main #redux_ajax_overlay').fadeOut();

	           	} else {
	           		$('.redux-main #redux_ajax_overlay').fadeOut();
	           		$(".convert-info p").append(response.value);
	           	}
                
        	});
        });
});


