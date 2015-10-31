<?php

use Xs\User;
use Illuminate\Database\Seeder;

/**
 * Seed the `users` table.
 */
class UsersTableSeeder extends Seeder {

	/**
	 *
	 */
	public function run()
	{
		# Clear existing
		DB::table('users')->delete();

		# Seed data
		User::create([
			'firstname'		=> 'Mark',
			'lastname'		=> 'Potter',
			'email'			=> 'markkpotter@gmail.com',
			'password'		=> Hash::make('Abc123'),
		]);
		// UserSettings is automatically created by the User model
	}

}
