<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

/**
 * Run the database seeds.
 */
class DatabaseSeeder extends Seeder {

	/**
	 *
	 */
	public function run()
	{
		Model::unguard();

		$this->call('UsersTableSeeder');

		$this->command->info('Tables seeded!');
	}

}
