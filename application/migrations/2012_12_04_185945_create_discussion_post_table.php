<?php

class Create_Discussion_Post_Table {

	private $name = 'discussions_posts';

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creat Table
		Schema::create($this->name, function($table){
			$table->increments('id');
			$table->boolean('deleted')->default(0);
			$table->integer('discussion_id');
			$table->text('post');
			$table->integer('user_id');
			$table->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		// Drop Table
		Schema::drop($this->name);
	}

}