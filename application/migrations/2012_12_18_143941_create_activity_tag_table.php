<?php

class Create_Activity_Tag_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create Table
		Schema::create('activities_tags', function($table){
			$table->increments('id');
			$table->integer('activity_id');
			$table->integer('record_id')->nullable();
			$table->string('model')->default('');
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
		Schema::drop('activities_tags');
	}

}