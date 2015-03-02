<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsOrganizationFieldToPersonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('persons', function(Blueprint $table)
		{
			$table->boolean('is_organization')->after('auto_generated')->default(false);
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
			$table->dropColumn('is_organization');
		});
	}

}
