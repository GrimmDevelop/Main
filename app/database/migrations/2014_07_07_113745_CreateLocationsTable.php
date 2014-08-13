<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('locations', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();

            $table->string('name', 200);
            $table->string('asciiname', 200);
            $table->string('alternatenames', 5000);
            $table->decimal('latitude', 7, 5);
            $table->decimal('longitude', 7, 5);
            $table->char('feature_class', 1);
            $table->string('feature_code', 10);
            $table->char('country_code', 2);
            $table->string('cc2', 60);
            $table->string('admin1_code', 20);
            $table->string('admin2_code', 80);
            $table->string('admin3_code', 20);
            $table->bigInteger('admin4_code');
            $table->bigInteger('population');
            $table->integer('elevation')->unsigned();
            $table->integer('dem')->unsigned();
            $table->string('timezone', 40);
            $table->date('modification_date');

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
		Schema::drop('locations');
	}

}
