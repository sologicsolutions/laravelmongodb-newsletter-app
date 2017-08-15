<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contacts', function($collection)
		{
			$collection->increments('id');
			$collection->string('firstname', 50);
			$collection->string('lastname', 50)->nullable();
			$collection->string('email', 100);
			$collection->string('password', 32);
			$collection->date('created_at');
			$collection->date('verify_at')->nullable();
			$collection->string('token', 50)->nullable();
			//$collection->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contacts');
	}

}
