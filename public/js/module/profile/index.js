/**
 * Profile JS
 */
require(['jquery', 'bootstrap', 'component/editable', 'component/confirm'],
	function($, nBootstrap, oEditable){
		// Make profile fields editable in-line
		oEditable({
			validate: {
				alerts: function(value){
					if (value.indexOf('Text') > -1 && isNaN($('[data-name="phone"]').text()))
						return 'Please enter your phone number first.';
				}
			}
		});
	}
);
