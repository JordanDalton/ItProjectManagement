<?php

class Create_User_Table {

	private $name = 'users';

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
            $table->string('email', 255)->index();
            $table->boolean('deleted')->default(0);
            $table->string('firstname');
            $table->string('lastname');
			$table->string('name');
            $table->string('username', 30)->index();
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