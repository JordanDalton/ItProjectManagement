<?php

class Create_Project_Table {

	private $name = 'projects';

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
			$table->text('description')->nullable();
			$table->integer('discussion_count')->default(0)->unsigned();
			$table->integer('document_count')->default(0)->unsigned();
			$table->integer('member_count')->default(0)->unsigned();
			$table->string('name');
			$table->integer('project_category_id')->nullable();
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