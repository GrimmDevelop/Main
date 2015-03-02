<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInNameOfFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('letter_sender', function(Blueprint $table)
		{
			$table->integer('in_name_of_id')->unsigned()->default(null)->index();
		});

		Schema::table('letter_receiver', function(Blueprint $table)
		{
			$table->integer('in_name_of_id')->unsigned()->default(null)->index();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('letter_sender', function(Blueprint $table)
		{
			$table->dropColumn('in_name_of_id');
		});

		Schema::table('letter_receiver', function(Blueprint $table)
		{
			$table->dropColumn('in_name_of_id');
		});
	}

}
