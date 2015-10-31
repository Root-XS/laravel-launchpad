/**
 * Quick confirmation dialogue.
 */
define(['jquery'], function($){
	$('.confirm').click(function(e){
		e.preventDefault();
		var strMessage = $(this).data('confirm-message') || 'Are you sure?';
		if (confirm(strMessage)) {
			// Links
			if ($(this).attr('href'))
				window.location = $(this).attr('href');
			// Submit buttons
			else
				$(this).closest('form').submit();
		}
	});
});
