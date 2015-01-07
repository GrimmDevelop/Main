<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('persons', function(Blueprint $table)
		{
			$table->increments('id');

            $table->string('name_2013')->unique();

			$table->timestamps();
		});

        Schema::create('person_informations', function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('person_id')->unsigned()->index();
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
		Schema::drop('persons');
	}

}
