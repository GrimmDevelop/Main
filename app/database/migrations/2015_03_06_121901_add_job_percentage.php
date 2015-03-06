<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobPercentage extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('job_status', function(Blueprint $table)
        {
            $table->integer('percentage')->unsigned();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('job_status', function(Blueprint $table)
        {
            $table->dropColumn('percentage');
        });
	}

}
