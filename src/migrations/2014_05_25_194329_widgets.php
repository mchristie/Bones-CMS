<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Widgets extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('widgets', function($table) {
			$table->increments('id');
			$table->integer('site_id')->indexed();
			$table->string('type');
			$table->string('area')->indexed();
			$table->text('settings')->nullable();
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
		Schema::drop('widgets');
	}

}
