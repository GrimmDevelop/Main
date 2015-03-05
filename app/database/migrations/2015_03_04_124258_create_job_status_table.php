<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('job_status', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('token', 60)->unique();
            $table->string('handler');
            $table->text('progress');
            $table->tinyInteger('status');
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
		Schema::drop('job_status');
	}

}
