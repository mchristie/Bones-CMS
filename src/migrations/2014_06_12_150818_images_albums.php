<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImagesAlbums extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('albums', function($table) {
			$table->increments('id');
			$table->integer('site_id')->index()->nullable();
			$table->string('title')->index();
			$table->timestamps();
		});

		Schema::create('images', function($table) {
			$table->increments('id');
			$table->integer('site_id')->index()->nullable();
			$table->integer('album_id')->index();
			$table->integer('status');
			$table->string('filename');
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
		Schema::drop('albums');
		Schema::drop('images');
	}

}
