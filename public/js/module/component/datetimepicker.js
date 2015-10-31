/**
 * Wrapper for jQuery datetimepicker plugin.
 *
 * @see http://xdsoft.net/jqplugins/datetimepicker/
 * @return object
 */
define(['jquery', 'jquery.datetimepicker'], function($){
	return function(){
		// css
		var link = document.createElement('link');
		link.type = 'text/css';
		link.rel = 'stylesheet';
		link.href = '/js/lib/jquery.datetimepicker.min.css';
		document.getElementsByTagName('head')[0].appendChild(link);

		// js
		var oDate = new Date();
		$('.datetimepicker').datetimepicker({
			minDate: oDate,
			minTime: oDate,
			onChangeDateTime: function(oDateTime, $input){
				if (oDateTime) {
					var mMinTime = '12:00 AM';
					if (oDateTime.getFullYear() === oDate.getFullYear() && oDateTime.getDate() === oDate.getDate()) {
						mMinTime = oDate;
						if (oDateTime.getTime() < oDate.getTime())
							$input.val('');
					}
					this.setOptions({
						minTime: mMinTime
					});
				}
			},
			format: 'Y/m/d g:00 A',
			formatTime: 'g:00 A'
		});
	}
});
