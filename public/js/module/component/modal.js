/**
 * Define general modal behavior.
 *
 * @param string strActionUrl The AJAX target URL.
 * @return object
 */
define(['jquery', 'bootstrap'], function($){
	return function(strActionUrl){

		/**
		 * Sets up AJAX click handling for Next & Previous buttons in a modal.
		 *
		 * @param object oElement The DOM element that gets the click event.
		 * @param string strGivenActionUrl The URL path for the AJAX call.
		 * @param string strUrlSuffix Suffix that can be added to the end of
		 *                            strActionUrl, like a query string.
		 */
		var setClickEvent = function(oElement, strGivenActionUrl, strUrlSuffix){
			oElement.unbind('click').click(function(){

				$('#guide-error').hide();
				$('#guide-loading').show();

				// Collect form data
				var bEmpty = true,
					oForm = $('#guide-modal form'),
					oArgs = {
					'step' : $(this).hasClass('handoff')
						? 0
						: $(this).hasClass('next')
							? parseInt($('#guide-step').val()) + 1
							: parseInt($('#guide-step').val()) - 1
					};
				if (oForm.length && $(this).hasClass('next')) {
					var aFormValues = oForm.serializeArray();
					for (iKey in aFormValues) {
						var oInput = aFormValues[iKey];
						oArgs[oInput.name] = oInput.value;
						if ('_token' != oInput.name && oInput.value)
							bEmpty = false;
					}
				}

				// Not-empty validation
				if (oForm.length && oForm.hasClass('not-empty') && bEmpty) {
					oForm.find('.alert-danger').show();

				// Send request
				} else {
					goAjax(strGivenActionUrl, oArgs, strUrlSuffix);
				}
			});
		}

		/**
		 * Send the AJAX request and update the modal with results.
		 *
		 * @param string strGivenActionUrl The URL path for the AJAX call.
		 * @param object oArgs Object containing HTTP request arguments.
		 * @param string strUrlSuffix Suffix that can be added to the end of
		 *                            strActionUrl, like a query string.
		 */
		var goAjax = function(strGivenActionUrl, oArgs, strUrlSuffix){
			oArgs = oArgs || {};

			// Include CSRF token
			if (!oArgs.hasOwnProperty('_token') && $('.modal-body').data('token'))
				oArgs._token = $('.modal-body').data('token');

			$.ajax({
				data: oArgs,
				method: 'POST',
				url: strGivenActionUrl + (strUrlSuffix || ''),
				success: function(strData, strStatus, jqXHR){
					strActionUrl = strGivenActionUrl;
					$('#guide-loading').hide();
					$('#guide-modal .modal-body').html(strData);
					$('.guide-form').submit(function(e){
						e.preventDefault();
						$('.guide-modal-nav.next').click();
					});

					// Process callback function (if given)
					var strCallback;
					if (strCallback = jqXHR.getResponseHeader('X-Xs-GuideCallback')) {
						require([strCallback], function(oCallback){
							if (typeof oCallback == 'function')
								oCallback();
						});
					}

					// Modal title
					if (jqXHR.getResponseHeader('X-Xs-GuideTitle'))
						$('.modal-title').html(jqXHR.getResponseHeader('X-Xs-GuideTitle'));

					// Nav buttons
					setNavLabel(
						'next',
						jqXHR.getResponseHeader('X-Xs-GuideLabelNext'),
						jqXHR.getResponseHeader('X-Xs-GuideLastStep'),
						jqXHR.getResponseHeader('X-Xs-GuideHandoff'),
						jqXHR.getResponseHeader('X-Xs-GuideUrlSuffix')
					);
					setNavLabel(
						'prev',
						jqXHR.getResponseHeader('X-Xs-GuideLabelPrev'),
						jqXHR.getResponseHeader('X-Xs-GuideStep') == 0,
						jqXHR.getResponseHeader('X-Xs-GuideParachute'),
						jqXHR.getResponseHeader('X-Xs-GuideUrlSuffix')
					);

					// Step counter
					$('#guide-step').val(jqXHR.getResponseHeader('X-Xs-GuideStep'));

					// End of survey
					if (jqXHR.getResponseHeader('X-Xs-GuideDone'))
						$('#guide-modal').modal('hide');
				},
				error: function(jqXHR, strStatus, errorThrown){
					$('#guide-loading').hide();
					$('#guide-error').show();
				}
			});
		}

		/**
		 * Helper function to set nav button labels. Also sets click events.
		 *
		 * @param string strDirection Options: 'prev' or 'next'
		 * @param string strLabel Custom button label.
		 * @param bool bBound Is one of the upper or lower bounds?
		 * @param string strBoundActionUrl Click event for the bound button.
		 * @param string strUrlSuffix Suffix that can be added to the end of
		 *                            strActionUrl, like a query string.
		 */
		var setNavLabel = function(strDirection, strLabel, bBound, strBoundActionUrl, strUrlSuffix){
			var oElement = $('.guide-modal-nav.' + strDirection),
				strBefore = '',
				strAfter = '';

			// Label the button
			if ('prev' == strDirection) {
				if (!bBound)
					strBefore = '<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;';
				if (!strLabel)
					strLabel = bBound ? 'Maybe Later' : 'Previous';
			}
			if ('next' == strDirection) {
				if (!bBound)
					strAfter = '&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right"></span>';
				if (!strLabel)
					strLabel = bBound ? 'Finish!' : 'Next';
			}
			oElement.html(strBefore + strLabel + strAfter);

			// Update the click event
			oElement.removeClass('handoff');
			if (bBound) {
				if (strBoundActionUrl) {
					oElement.addClass('handoff');
					setClickEvent(oElement, strBoundActionUrl, strUrlSuffix);
				} else {
					oElement.unbind('click').click(function(){
						$('#guide-modal').modal('hide');
					});
				}
			} else {
				setClickEvent(oElement, strActionUrl, strUrlSuffix);
			}
		}

		// Init
		goAjax(strActionUrl);
		setNavLabel('prev', null, true);
		setNavLabel('next');

	};
});
