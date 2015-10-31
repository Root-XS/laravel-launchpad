<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailProvider' => env('XS_MAIL_PROVIDER', 'laravel'), // @see app/lib/Notify for options
	'textProvider' => env('XS_TEXT_PROVIDER', 'eztexting'), // @see app/lib/Notify for options
	'textProvider2' => env('XS_TEXT_PROVIDER2'), // @see app/lib/Notify for options

	'eztexting' => [
		'username' => 'rootxs',
		'password' => 'NQsHPcw9yq',
	],
	'mailchimp' => [
		'listId' => env('MAILCHIMP_LIST_ID', ''),
	],
	'mailgun' => [
		'domain' => env('MAILGUN_DOMAIN', 'sandbox93f92ad52d4b476abd92b01ad581a6ec.mailgun.org'),
		'secret' => env('MAILGUN_SECRET', 'key-dd36be41ccb86304bfc5fc8fb3c2621a'),
	],
	'nexmo' => [
		'key' => env('NEXMO_KEY'),
		'secret' => env('NEXMO_SECRET'),
	],

];
