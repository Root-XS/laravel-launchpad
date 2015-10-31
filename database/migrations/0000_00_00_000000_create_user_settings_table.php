<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * The "user_settings" table is a normalization table (1-to-1 relationship
 * with "users") that holds User contact and subscription settings.
 */
class CreateUserSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_settings', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned();
			$table->boolean('my_setting')->default(1); // Description of this setting
			$table->timestamps();

			$table->primary('user_id');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
		Schema::drop('user_settings');
	}

}
