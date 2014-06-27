<?php

class Create_Project_Category_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create Table
		Schema::create(Project_Category::$table, function($table){
			$table->increments('id');
			$table->string('name');
			$table->timestamps();
		});

		// Insert default data
		$this->data();
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		// Drop Table
		Schema::drop(Project_Category::$table);
	}

	/**
	 * Default data to be inserted into the table.
	 * @return void
	 */
	public function data()
	{
		$records = array(
			array('name' => 'Current'),
			array('name' => 'Future'),
			array('name' => 'Closed'),
		);

		// Loop through all of the records
		foreach($records as $record)
		{
			// Create new project category
			Project_Category::create($record);
		}
	}

}