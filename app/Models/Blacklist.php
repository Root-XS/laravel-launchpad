<?php

namespace Xs;

use Illuminate\Database\Eloquent\Model;

/**
 * The blacklist_emails table holds unsubscribed email addresses of non-members.
 */
class Blacklist extends Model {

	/**
	 * Unconventional table name.
	 *
	 * @var string $table
	 */
	protected $table = 'blacklist_emails';

	/**
	 * Allow email to be set in the constructor.
	 *
	 * @var array $fillable
	 */
	protected $fillable = ['email'];

	/**
	 * Generate an ID hash before saving.
	 *
	 * @return bool
	 */
	public function save(array $options = [])
	{
		if (!$this->id)
			$this->id = str_random(32);
		return parent::save($options);
	}

}
