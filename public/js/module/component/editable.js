/**
 * Wrapper for X-editable lib.
 *
 * @see http://vitalets.github.io/x-editable/index.html
 * @return object
 */
define(['jquery', 'bootstrap', 'bootstrap-editable'], function($){
	return function(oDefaultOptions){
		// css
		var link = document.createElement('link');
		link.type = 'text/css';
		link.rel = 'stylesheet';
		link.href = '/js/lib/bootstrap-editable.css';
		document.getElementsByTagName('head')[0].appendChild(link);
		// js
		$.fn.editable.defaults.mode = 'inline';
		$('.editable').each(function(){
			var oOptions = {
					ajaxOptions: {
						dataType: 'json'
					},
					highlight: '#40BCC9',
					params: {
						'_token': $(this).data('token')
					},
					success: function(oResponse, mNewValue){
						if (oResponse.error)
							return oResponse.error.message;
					},
					error: function(oResponse, mNewValue){
						return (oResponse && oResponse.error.message)
							? oResponse.error.message
							: 'Server error. Please report this problem.';
					}
				};
			if (oDefaultOptions.validate.hasOwnProperty($(this).data('name')))
				oOptions.validate = oDefaultOptions.validate[$(this).data('name')];
			$(this).editable(oOptions);
		});
	}
});
