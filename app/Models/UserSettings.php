<?php

namespace Xs;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class UserSettings extends Model {

	/**
	 * Wanted both the model & table names to be plural.
	 */
	protected $table = 'user_settings';

	/**
	 * Primary Key is not the default "id"
	 */
	protected $primaryKey = 'user_id';

	/**
	 * @return User
	 */
	public function user()
	{
		return $this->belongsTo('Xs\User');
	}

}
