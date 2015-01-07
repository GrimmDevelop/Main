<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutogeneratedFieldsToPersonTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('persons', function(Blueprint $table)
		{
			$table->boolean('auto_generated')->default(false)->after('name_2013');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('persons', function(Blueprint $table)
		{
			$table->dropColumn('auto_generated');
		});
	}

}
