<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * The "users" table holds the individual User records for purposes of
 * authentication and contact info.
 */
class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email', 255); // User's email address (unique)
			$table->string('password', 60); // Hashed password
			$table->rememberToken();
			$table->string('firstname', 20); // User's first name
			$table->string('lastname', 20); // User's last name
			$table->timestamps();

			$table->unique('email');

			$table->engine = 'InnoDB';
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
