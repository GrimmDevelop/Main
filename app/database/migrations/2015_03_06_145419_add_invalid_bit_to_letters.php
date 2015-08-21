<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvalidBitToLetters extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('letters', function(Blueprint $table)
		{
			$table->tinyInteger('valid')->default(1);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('letters', function(Blueprint $table)
		{
			$table->dropColumn('valid');
		});
	}

}
