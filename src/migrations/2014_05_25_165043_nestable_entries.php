<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NestableEntries extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('entries', function($table) {
			$table->integer('parent_id')->nullable();
			$table->integer('lft')->nullable();
			$table->integer('rgt')->nullable();
			$table->integer('depth')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('entries', function($table) {
			$table->dropColumn('parent_id');
			$table->dropColumn('lft');
			$table->dropColumn('rgt');
			$table->dropColumn('depth');
		});
	}

}
