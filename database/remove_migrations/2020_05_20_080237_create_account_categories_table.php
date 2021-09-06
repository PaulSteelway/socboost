<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('account_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name_ru', 256)->unique();
			$table->string('name_en', 256)->unique();
			$table->boolean('status')->default(1);
			$table->string('url', 256)->nullable()->unique();
			$table->text('details_ru')->nullable();
			$table->text('details_en')->nullable();
			$table->timestamps();
			$table->string('icon_class', 64)->nullable();
			$table->string('icon_img', 1024)->nullable();
			$table->string('icon_img_active', 1024)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('account_categories');
	}

}
