<?php

class Create_Project_Document_Table {

	private $name = 'projects_documents';

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
			$table->string('filename');
			$table->integer('project_id');
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