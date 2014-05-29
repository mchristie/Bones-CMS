<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FieldDataFieldId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('field_data', function($table) {
			$table->dropColumn('field_type');

			$table->integer('field_id')->after('entry_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('field_data', function($table) {
			$table->dropColumn('field_id');

			$table->string('field_type')->after('entry_id');
		});
	}

}
