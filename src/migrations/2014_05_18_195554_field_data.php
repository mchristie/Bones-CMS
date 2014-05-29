<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FieldData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('field_data', function($table) {
			$table->increments('id');
			$table->integer('entry_id');
			$table->string('field_type');
			$table->integer('integer_data')->nullable();
			$table->string('string_data')->nullable();
			$table->text('text_data')->nullable();
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
		Schema::drop('field_data');
	}

}
