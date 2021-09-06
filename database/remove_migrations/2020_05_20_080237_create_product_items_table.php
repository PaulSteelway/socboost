<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username', 64);
			$table->string('password', 64);
			$table->integer('product_id')->unsigned()->index('fk_product_items_1_idx');
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
		Schema::drop('product_items');
	}

}
