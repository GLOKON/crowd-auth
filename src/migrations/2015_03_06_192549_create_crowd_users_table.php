<?php

/*
 * This file is part of Laravel CrowdAuth
 *
 * (c) Daniel McAssey <hello@glokon.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCrowdUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('crowd_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('crowd_key')->unique();
			$table->string('username');
			$table->string('email');
			$table->string('display_name');
			$table->string('first_name');
			$table->string('last_name');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('crowd_users');
	}

}
