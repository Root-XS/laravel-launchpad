<?php

namespace Xs;

use AWS;
use App, Config, Crypt, Log, Mail;

/**
 * Notification-sending class.
 *
 * Wrapper for email, texts, and push notifications.
 */
class Notify {

	/**
	 * Constants
	 *
	 * @var string FROM_NAME
	 * @var string FROM_EMAIL
	 */
	const FROM_NAME = 'RootXS';
	const FROM_EMAIL = 'mail@rootxs.org';

	/**
	 * Send an email notification.
	 *
	 * @param string $strTo Recipient email address.
	 * @param string $strSubject Email subject line.
	 * @param string $strTemplate View script.
	 * @param array $aTemplateParams Array of values to pass on to the view script.
	 */
	public static function mail($strTo, $strSubject, $strTemplate = null, array $aTemplateParams = [])
	{
		// Check recipient settings
		$oRecip = User::with('settings')->where('email', $strTo)->first();
		if ($oRecip && !1 == $oRecip->settings->notify || Blacklist::find($strTo))
			return true;
		$aTemplateParams['strEncryptedEmail'] = $oRecip ? false : Crypt::encrypt($strTo);

		// Format the View template string
		if (!$strTemplate)
			$strTemplate = 'default';
		if (strpos($strTemplate, 'emails.') !== 0)
			$strTemplate = 'emails.' . $strTemplate;

		// Send
		$strMethod = 'mail' . ucfirst(Config::get('services.mailProvider'));
		self::$strMethod(
			$strTo,
			$strSubject,
			$strTemplate,
			$aTemplateParams
		);
	}

	/**
	 * Send a text message notification.
	 *
	 * @param string $strPhone Recipient phone number.
	 * @param string $strMessage Text message content.
	 * @param int $iUserId User ID, used to select the text provider.
	 */
	public static function text($strPhone, $strMessage, $iUserId = 999)
	{
		$strMethod = 'text' . ucfirst($iUserId <= 15 && App::environment('production')
			? Config::get('services.textProvider2')
			: Config::get('services.textProvider')
		);
		self::$strMethod($strPhone, $strMessage);
	}

	/**
	 * Send a push notification.
	 */
	public static function push()
	{
		// @todo
	}

	/**
	 * Send an email via Laravel's native Mail.
	 *
	 * @param string $strTo Recipient email address.
	 * @param string $strSubject Email subject line.
	 * @param string $strTemplate View script.
	 * @param array $aViewParams Array of values to pass on to the view script.
	 */
	protected static function mailLaravel($strTo, $strSubject, $strTemplate, array $aViewParams)
	{
		Mail::send($strTemplate, $aViewParams, function($message) use ($strTo, $strSubject) {
			$message->from(Notify::FROM_EMAIL, Notify::FROM_NAME)
				->to($strTo)
				->subject($strSubject);
		});
	}

	/**
	 * Send an email via Amazon SES.
	 *
	 * @param string $strTo Recipient email address.
	 * @param string $strSubject Email subject line.
	 * @param string $strTemplate View script.
	 * @param array $aViewParams Array of values to pass on to the view script.
	 */
	protected static function mailSes($strTo, $strSubject, $strTemplate, array $aViewParams)
	{
		AWS::createClient('ses')->sendEmail([
			'Source' => Notify::FROM_NAME . ' <' . Notify::FROM_EMAIL . '>',
			'Destination' => [
				'ToAddresses' => [$strTo],
			],
			'Message' => [
				'Subject' => [
					'Data' => $strSubject,
					'Charset' => 'UTF8',
				],
				'Body' => [
					'Html' => [
						'Data' => view($strTemplate, $aViewParams)->render(),
						'Charset' => 'UTF8',
					],
				],
			],
			'ReturnPath' => 'admin@disciplefy.org',
		]);
	}

	/**
	 * Send a text message via EZtexting.
	 *
	 * @param string $strPhone Recipient phone number.
	 * @param string $strMessage Text message content.
	 */
	protected static function textEztexting($strPhone, $strMessage)
	{
		$aData = [
			'User'          => Config::get('services.eztexting.username'),
			'Password'      => Config::get('services.eztexting.password'),
			'PhoneNumbers'  => [$strPhone],
			'Subject'       => 'Disciplefy',
			'Message'       => $strMessage,
			'MessageTypeID' => 1, // "express" vs legacy "standard" (2)
			//'StampToSend'   => '1305582245', // timestamp for delayed send
		];

		$oCurl = curl_init('https://app.eztexting.com/sending/messages?format=json');
		curl_setopt($oCurl, CURLOPT_POST, 1);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($aData));
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		$strResponse = curl_exec($oCurl);
		curl_close($oCurl);

		$oJson = json_decode($strResponse);
		$oJson = $oJson->Response;

		if ('Failure' == $oJson->Status) {
			$errors = [];
			if (!empty($oJson->Errors))
				$errors = $oJson->Errors;
			Log::error(
				"EZtexting API\n",
				'Status: ' . $oJson->Status . "\n" .
				'Errors: ' . implode(', ' , $errors) . "\n"
			);
		// } else {
			// See this link for response payload (scroll down to JSON response example):
			// @see https://www.eztexting.com/developers/sms-api-documentation/rest#Sending
		}
	}

	/**
	 * Send a text message via Nexmo. Uses short code 96167.
	 *
	 * @param string $strPhone Recipient phone number.
	 * @param string $strMessage Text message content.
	 */
	protected static function textNexmo($strPhone, $strMessage)
	{
		// set up
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // hide JSON output
		curl_setopt(
			$ch,
			CURLOPT_URL,
			'https://rest.nexmo.com/sc/us/alert/json?api_key=' . Config::get('services.nexmo.key')
				. '&api_secret=' . Config::get('services.nexmo.secret')
				. '&to=1' . $strPhone . '&text=' . urlencode($strMessage)
		);

		// exec
		$strJson = curl_exec($ch);
		// @TODO error checking
		//dd($strJson);

		// tear down
		curl_close($ch);
	}

}
