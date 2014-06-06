<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLettersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('letters', function(Blueprint $table)
		{
			$table->increments('id');

			$table->float('code');
			$table->string('language');
			$table->date('date');

			$table->timestamps();
		});

		Schema::create('letter_informations', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('letter_id')->index();
			$table->string('code')->index();

			$table->text('data');

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
		Schema::drop('letters');
		Schema::drop('letter_informations');
	}

}
