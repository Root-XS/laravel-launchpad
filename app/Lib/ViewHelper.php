<?php

namespace Xs;

use Braintree_CreditCard, Braintree_PaypalAccount;
use App, Auth;

/**
 * View Helper class
 *
 * Provides helper functions to clean up views.
 */
class ViewHelper {

	/**
	 * Generate HTML for displaying a Braintree payment method.
	 *
	 * @param Braintree_CreditCard|Braintree_PaypalAccount
	 * @return string HTML
	 */
	public static function displayPaymentMethod($oPaymentMethod)
	{
		$strHtml = '<div class="pmt-method"><img src="' . $oPaymentMethod->imageUrl . '">';
		if ($oPaymentMethod instanceof Braintree_CreditCard)
			$strHtml .= '******' . substr($oPaymentMethod->maskedNumber, 6);
		elseif (!$oPaymentMethod instanceof Braintree_PaypalAccount)
			App::abort(500, 'Invalid payment method given.');
		return $strHtml . '</div>';
	}
}

