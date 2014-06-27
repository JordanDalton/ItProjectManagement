<?php

class Create_Project_User_Table {

	private $name = 'projects_users';

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
			$table->integer('project_id');
			$table->integer('user_id');
			$table->boolean('leader')->default(0);
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