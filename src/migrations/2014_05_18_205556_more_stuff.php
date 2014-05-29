<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoreStuff extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('channels', function($table) {
			$table->string('list_view')->nullable();
			$table->string('entry_view')->nullable();
		});

		Schema::table('entries', function($table) {
			$table->integer('site_id')->nullable();
			$table->string('view')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('channels', function($table) {
			$table->dropColumn('list_view');
			$table->dropColumn('entry_view');
		});

		Schema::table('entries', function($table) {
			$table->dropColumn('site_id');
			$table->dropColumn('view');
		});
	}

}
