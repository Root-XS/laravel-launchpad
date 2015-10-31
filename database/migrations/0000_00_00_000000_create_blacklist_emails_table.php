<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlacklistEmailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Holds unsubscribed email addresses of non-members
		Schema::create('blacklist_emails', function(Blueprint $table)
		{
			$table->string('id', 32);
			$table->string('email');
			$table->timestamps();

			$table->primary('id');
			$table->unique('email');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('blacklist_emails');
	}

}
