<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndiciesLettersLoactionsPersons extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('letters', function(Blueprint $table)
		{
			$table->index('code');
			$table->foreign('from_id')
				->references('id')->on('locations')
				->onDelete('set null');
			$table->foreign('to_id')
				->references('id')->on('locations')
				->onDelete('set null');
		});

		Schema::table('letter_information', function(Blueprint $table)
		{
			$table->foreign('letter_id')
				->references('id')->on('letters')
				->onDelete('cascade');
		});

		Schema::table('letter_sender', function(Blueprint $table)
		{
			$table->foreign('letter_id')
				->references('id')->on('letters')
				->onDelete('cascade');
			$table->foreign('person_id')
				->references('id')->on('persons')
				->onDelete('cascade');
		});

		Schema::table('letter_receiver', function(Blueprint $table)
		{
			$table->foreign('letter_id')
				->references('id')->on('letters')
				->onDelete('cascade');
			$table->foreign('person_id')
				->references('id')->on('persons')
				->onDelete('cascade');
		});

		Schema::table('locations', function(Blueprint $table) {
			$table->index('name');
			$table->index('asciiname');
		});

		Schema::table('geo_cache', function(Blueprint $table)
		{
			DB::statement('ALTER TABLE `geo_cache` CHANGE `geo_id` `geo_id` INT( 10 ) UNSIGNED NOT NULL');

			$table->index('geo_id');
			$table->foreign('geo_id')
				->references('id')->on('locations')
				->onDelete('cascade');
		});

		Schema::table('persons', function(Blueprint $table) {
			// 
		});

		Schema::table('person_information', function(Blueprint $table)
		{
			$table->foreign('person_id')
				->references('id')->on('persons')
				->onDelete('cascade');
		});

		Schema::table('person_cache', function(Blueprint $table)
		{
			$table->index('person_id');
			$table->foreign('person_id')
				->references('id')->on('persons')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		
	}

}
