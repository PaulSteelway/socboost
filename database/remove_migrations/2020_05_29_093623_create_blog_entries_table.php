<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogEntriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blog_entries', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('blog', 191)->default('main');
			$table->dateTime('publish_after')->nullable();
			$table->string('slug', 191);
			$table->string('title', 191);
			$table->string('author_name', 191)->nullable();
			$table->string('author_email', 191)->nullable();
			$table->string('author_url', 191)->nullable();
			$table->text('image')->nullable();
			$table->text('content');
			$table->text('summary')->nullable();
			$table->string('page_title', 191)->nullable();
			$table->string('description', 191)->nullable();
			$table->text('meta_tags')->nullable();
			$table->boolean('display_full_content_in_feed')->nullable();
			$table->timestamps();
			$table->index(['publish_after','blog','slug'], 'public');
			$table->unique(['slug','blog'], 'slug');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('blog_entries');
	}

}
