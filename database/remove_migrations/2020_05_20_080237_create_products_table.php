<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_category_id')->unsigned()->nullable()->index('fk_products_1_idx');
			$table->string('name_ru', 256);
			$table->string('name_en', 256);
			$table->boolean('status')->default(1);
			$table->string('url', 256)->nullable();
			$table->timestamps();
			$table->string('icon_class', 64)->nullable();
			$table->string('icon_img', 1024)->nullable();
			$table->string('icon_img_active', 1024)->nullable();
			$table->decimal('price', 12,2)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products');
	}

}
