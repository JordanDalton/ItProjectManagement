<?php

class Create_Activity_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create Table
		Schema::create('activities', function($table){
			$table->increments('id');
			$table->integer('activity_type_id')->nullable();
			$table->integer('record_id')->nullable();
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
		Schema::drop('activities');
	}

}