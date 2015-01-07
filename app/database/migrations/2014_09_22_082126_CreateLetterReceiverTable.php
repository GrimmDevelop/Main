<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterReceiverTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('letter_receiver', function(Blueprint $table)
		{
			$table->integer('letter_id')->unsigned()->index();
            $table->integer('person_id')->unsigned()->index();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('letter_receiver');
	}

}
