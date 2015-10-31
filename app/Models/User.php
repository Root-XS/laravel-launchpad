<?php

namespace Xs;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use App, Carbon, Config, Log;
use Braintree_Customer, Braintree_Subscription;

/**
 *
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password'];
	protected $fillable = ['firstname', 'lastname', 'email', 'password'];

	/**
	 * Validation rules
	 *
	 * @var array $rules
	 * @var array $editRules
	 * @var array $aAlertTypes
	 */
	public static $rules = [
		'email' => 'required|email|unique:users',
		'password' => 'required|alpha_num|between:6,64',
		'firstname' => 'required|alpha|min:2',
		'lastname' => 'required|alpha|min:2',
		'city' => 'honeypot',
		'state' => 'required|honeytime:4', // it should take a real person at least 4 seconds to complete the form
	];
	public static $editRules = [
		'email' => 'email|unique:users',
		'password' => 'alpha_num|between:6,64',
		'firstname' => 'alpha|min:2',
		'lastname' => 'alpha|min:2',
	];
	public static $aAlertTypes = [
		'email' => 'Email',
		'text' => 'Text message',
	];

	/**
	 * Dependencies.
	 *
	 * @var Braintree_Subscription $oSubscription
	 */
	protected $oSubscription = null;

	/**
	 * Allow custom dynamic properties.
	 *
	 * @param string $strVarname The method to access.
	 * @return mixed
	 */
	public function __get($strVarName)
	{
		// First check self
		$strMethod = camel_case('get_' . $strVarName);
		if (method_exists($this, $strMethod) && 'getRememberToken' !== $strMethod) // @todo better way around this exception?
			return $this->$strMethod();

		// Then check parent
		else
			return parent::__get($strVarName);
	}

	/**
	 * Get User's full name (first & last).
	 *
	 * @return string
	 */
	public function fullname()
	{
		return $this->firstname . ' ' . $this->lastname;
	}

	/**
	 * Get HTML to display User's avatar.
	 *
	 * @param string $strSize sm, md, lg
	 * @param array $aOptions Array of attribute options for the HTML tag.
	 * @param bool $bUrlOnly Return only the URL (vs the entire HTML tag)?
	 * @return string HTML <img> tag
	 */
	public function avatar($strSize = 'sm', $aOptions = [], $bUrlOnly = false)
	{
		$aSizes = [
			'sm' => '50',
			'md' => '80',
			'lg' => '200',
		];
		if (!array_key_exists($strSize, $aSizes))
			App::abort(500, 'Invalid avatar size requested.');

		$aOptions = array_merge(['class' => 'img-responsive img-thumbnail'], $aOptions);
		$strSourceUrl = '//secure.gravatar.com/avatar/' . md5($this->email)
			. '?s=' . $aSizes[$strSize] . '&r=g';

		return $bUrlOnly
			? $strSourceUrl
			: '<img src="' . $strSourceUrl . '" class="' . $aOptions['class']
				. '" title="' . $this->fullname() . '" alt="' . $this->fullname() . '">';
	}

	/**
	 * @return UserSettings
	 */
	public function settings()
	{
		return $this->hasOne('Xs\UserSettings');
	}

	/**
	 * Add user_settings record for new users.
	 *
	 * @param array $options
	 * @return bool
	 */
	public function save(array $options = [])
	{
		$bIsNew = $this->id ? false : true;
		if ('' === $this->phone)
			$this->phone = null;
		$bReturn = parent::save($options);

		if ($bIsNew) {

			// Add user_settings record for new users.
			$this->settings()->save(new UserSettings());

			// Add to MailChimp
			try {
				App::make('Mailchimp')->lists->subscribe(
					Config::get('services.mailchimp.listId'),
					['email' => $this->email],
					null,
					'html',
					false // don't send confirmation email
				);
			} catch (\Exception $e) {
				Log::error($e->getMessage());
			}
		}

		return $bReturn;
	}

	/// REQUIRED LARAVEL CONVENTIONS ///

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	/**
	 * Session token getter.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}

	/**
	 * Session token setter.
	 *
	 * @param str $value
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	/**
	 * Field name for storing the session token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

}
