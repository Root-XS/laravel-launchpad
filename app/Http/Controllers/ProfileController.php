<?php

namespace Xs\Http\Controllers;

use Xs\User;
use Braintree_Customer, Braintree_PaymentMethod;
use App, Auth, Hash, Input, Lang, Password, Redirect, Request, Validator;

class ProfileController extends Controller
{

	/**
	 * Properties.
	 *
	 * @var array $aViewless List of GET methods that do not have a View
	 *                            (usu. redirects to something else)
	 *
	 * @todo Incorporate these into Auth\PasswordController ???
	 * @var function $fnPasswordValidator
	 * @var string $strPasswordReqs
	 */
	protected $aViewless = [
		'delete-pmt-method',
	];
	protected $fnPasswordValidator;
	protected $strPasswordReqs;

	/**
	 * User object for the currently-viewed User.
	 *
	 * @property User $oUser
	 */
	protected $oUser;

	/**
	 * Require login.
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Set up.
	 */
	protected function general()
	{
		// Find User
		$this->oUser = (Input::get('u') || Input::get('pk'))
			? User::find(Input::get('u')?:Input::get('pk'))
			: Auth::user();
		if (!$this->oUser)
			App::abort(404, 'The requested User cannot be found.');

		// Permissions
		// @todo Eventually open this up so ppl can see each other's profiles?
		if ($this->oUser->id != Auth::id())
			App::abort(403, 'You don\'t have permission to view this page.');
	}

	/**
	 * User homepage.
	 */
	public function getIndex()
	{
		$this->layout->oUser = $this->oUser;
		$this->layout->oBtCustomer = Braintree_Customer::find(Auth::id());
	}

	/**
	 * AJAX page to process profile-edit requests.
	 */
	public function postEdit()
	{
		if (!Request::ajax())
			App::abort(400, 'Not a valid request.');
		if ((string) $this->oUser->id !== (string) Auth::id())
			App::abort(403, 'You cannot edit someone else\'s profile.');

		$oReturn = (object) ['error' => false];

		// Validate input
		$aFields = [
			'firstname',
			'lastname',
			'email',
			'phone',
			'gender',
			'alerts',
			'timezone',
			'notify',
		];
		if (!in_array(Input::get('name'), $aFields))
			App::abort(400, 'Field "' . Input::get('name') . '" is not valid.');
		$validator = Validator::make(
			[Input::get('name') => Input::get('value')],
			User::$editRules
		);
		if ($validator->passes()) {
			$this->oUser->{Input::get('name')} = Input::get('value');
			$this->oUser->push();
		} else {
			$oReturn->error = (object) [
				'message' => $validator->messages()->first(Input::get('name')),
			];
		}

		return json_encode($oReturn);
	}

	/**
	 * Process delete-pmt-method request.
	 */
	public function getDeletePmtMethod()
	{
		try {
			Braintree_PaymentMethod::delete(Input::get('token'));
			$strMessage = 'Payment method successfully deleted.';
			$iSuccess = 1;
		} catch (Exception $e) {
			$strMessage = 'Unable to delete payment method (invalid token).';
			$iSuccess = 0;
		}
		return Redirect::to('profile')
			->with('message', $strMessage)
			->with('success', $iSuccess);
	}

	/**
	 * Change password (form).
	 */
	public function getPassword()
	{
	}

	/**
	 * Change password (submit handler).
	 */
	public function postPassword()
	{
		// Password must contain uppercase, lowercase, and digit. Minimum 6 chars.
		$this->fnPasswordValidator = function(array $aCredentials){
			return strlen($aCredentials['password']) >= 6
				&& preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$/', $aCredentials['password']);
		};
		$this->strPasswordReqs = 'Password requirements:'
			. '<ul>'
				. '<li>minimum 6 characters</li>'
				. '<li>at least one uppercase letter</li>'
				. '<li>at least one lowercase letter</li>'
				. '<li>at least one number</li>'
				. '<li>password & confirmation must match</li>'
			. '</ul>';

		// Validate old & new passwords
		$aErrors = [];
		if (!Input::get('old_password') || !Input::get('password') || !Input::get('password_confirmation'))
			$aErrors[] = 'All fields are required.';
		if (Input::get('old_password') && !Hash::check(Input::get('old_password'), Auth::user()->password))
			$aErrors[] = 'Current password was incorrect.';
		if (Input::get('password') && !$this->fnPasswordValidator->__invoke(['password' => Input::get('password')]))
			$aErrors[] = $this->strPasswordReqs;
		if (Input::get('password') !== Input::get('password_confirmation'))
			$aErrors[] = 'New password confirmation did not match.';

		if (!empty($aErrors))
			return Redirect::back()->with('errors', $aErrors);

		// Update password
		Auth::user()->password = Hash::make(Input::get('password'));
		Auth::user()->save();

		// Refresh session. Win.
		if (Auth::attempt(['email' => Auth::user()->email, 'password' => Input::get('password')], true)) {
			return Redirect::to('/home')->withSuccess('Password updated successfully!');
		} else {
			return Redirect::back()->with(
				'errors',
				['We blew it! Something went wrong. Please try again.'] // "...or just give up"
			);
		}
	}

}
