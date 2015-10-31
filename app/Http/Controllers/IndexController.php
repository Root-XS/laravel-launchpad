<?php

namespace Xs\Http\Controllers;

use Xs\Blacklist;
use Xs\User;
use Auth, Crypt, Hash, Input, Redirect, Validator;

class IndexController extends Controller
{

	/**
	 * Parent class overrides.
	 *
	 * @property bool $bHasJS Does this controller have an associated JS file?
	 */
	protected $bHasJS = false;

	/**
	 *
	 */
	protected function general()
	{
		if (Auth::check())
			return Redirect::to('home');
	}

	/**
	 * Default homepage.
	 */
	public function getIndex()
	{
	}

	/**
	 * Unsubscribe
	 */
	public function getUnsubscribe()
	{
		// Validate request
		try {
			$aEmail = ['email' => Crypt::decrypt(Input::get('i'))];
		} catch (Exception $e) {
			$aEmail = ['email' => 'decryption failed'];
		}
		$oValidator = Validator::make(
			$aEmail,
			['email' => 'required|email|unique:blacklist_emails'],
			[
				'required' => 'No valid email was given.',
				'email' => 'No valid email was given (' . $aEmail['email'] . ').',
				'unique' => 'We\'ve already removed you from our mailing list.',
			]
		);

		// Add to Blacklist
		if ($oValidator->passes()) {
			$oBlacklist = new Blacklist($aEmail);
			$oBlacklist->save();
		}

		// Report results in View
		$this->layout->content->strEmail = $aEmail['email'];
		$this->layout->content->errors = $oValidator->messages();
	}

}
