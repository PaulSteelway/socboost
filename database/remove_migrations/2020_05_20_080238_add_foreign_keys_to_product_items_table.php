<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProductItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_items', function(Blueprint $table)
		{
			$table->foreign('product_id', 'fk_product_items_1')->references('id')->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_items', function(Blueprint $table)
		{
			$table->dropForeign('fk_product_items_1');
		});
	}

}
