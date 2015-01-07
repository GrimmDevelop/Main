<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameInformationsToInformationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::rename('letter_informations', 'letter_information');
		Schema::rename('person_informations', 'person_information');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::rename('letter_information', 'letter_informations');
		Schema::rename('person_information', 'person_informations');
	}

}
