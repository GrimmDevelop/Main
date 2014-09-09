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
			$table->string('empfaenger');
			$table->string('sprache');
			$table->string('hs');
			$table->string('inc');
			$table->string('dr_1');
			$table->string('dr_2');
			$table->string('dr_3');
			$table->string('dr_4');
			$table->string('dr_5');
			$table->string('dr_6');
			$table->string('dr_7');
			$table->string('faks');
			$table->string('konzept');
			$table->string('konzept_2');
			$table->string('abschrift');
			$table->string('abschr_2');
			$table->string('abschr_3');
			$table->string('abschr_4');
			$table->string('kopie');
			$table->string('auktkat');
			$table->string('auktkat_2');
			$table->string('auktkat_3');
			$table->string('auktkat_4');
			$table->string('erschl_aus');
			$table->string('empf_verm');
			$table->string('antw_verm');
			$table->string('zusatz');
			$table->string('zusatz_2');
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
