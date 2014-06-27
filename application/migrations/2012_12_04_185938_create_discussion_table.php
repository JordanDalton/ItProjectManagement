<?php

class Create_Discussion_Table {

	private $name = 'discussions';

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create Table
		Schema::create($this->name, function($table){
			$table->increments('id');
			$table->boolean('deleted')->default(0);
			$table->integer('project_id')->nullable();
			$table->integer('post_count')->default(1)->nullable();
			$table->string('title');
			$table->integer('user_id')->nullable();
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