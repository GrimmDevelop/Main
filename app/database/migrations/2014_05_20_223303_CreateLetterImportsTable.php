<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterImportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('letter_imports', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('gesehen_12');
			$table->decimal('code', 13, 4);
			$table->string('datum');
			$table->string('absendeort');
			$table->string('absort_ers');
			$table->string('empf_ort');
            $table->string('absender');
            $table->text('senders');
            $table->boolean('senders_contain_errors');
			$table->string('empfaenger');
            $table->text('receivers');
            $table->boolean('receivers_contain_errors');
			$table->string('sprache');
			$table->string('hs');
			$table->string('inc');
			$table->text('dr');
			$table->string('faks');
			$table->text('konzept');
			$table->text('abschrift');
			$table->string('kopie');
			$table->text('auktkat');
			$table->string('erschl_aus');
			$table->string('empf_verm');
			$table->string('antw_verm');
			$table->text('zusatz');
			$table->string('ba');
			$table->integer('nr_1992');
			$table->integer('nr_1997');
			$table->string('couvert');
			$table->string('verz_in');
			$table->string('beilage');
			$table->string('ausg_notiz');
			$table->string('tb_nr');
			$table->string('del');

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
		Schema::drop('letter_imports');
	}

}
