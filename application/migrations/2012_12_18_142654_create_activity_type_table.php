<?php

class Create_Activity_Type_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create Table
		Schema::create('activities_types', function($table){
			$table->increments('id');
			$table->string('name')->default('');
			$table->string('description')->default('');
			$table->string('Model')->default('');
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
		Schema::drop('activities_types');
	}

	public function data()
	{
		$inserts = array(
			array(
				'name' 			=> 'user.new',
				'description' 	=> 'New user has joined the website.',
				'model'			=> 'User',
			),
			array(
				'name' 			=> 'project.new',
				'description' 	=> 'New project was created.',
				'model'			=> 'Project',
			),
			array(
				'name' 			=> 'project.member.new',
				'description' 	=> 'New member was added to the project.',
				'model'			=> 'Project_User',
			),
			array(
				'name' 			=> 'discussion.new',
				'description' 	=> 'New project discussion topic.',
				'model'			=> 'Discussion',
			),
			array(
				'name' 			=> 'post.new',
				'description' 	=> 'New project discussion post.',
				'model'			=> 'Discussion_Post',
			),
			array(
				'name' 			=> 'document.new',
				'description' 	=> 'New project document was uploaded.',
				'model'			=> 'Project_Document',
			),
		);

		foreach($inserts as $insert)
		{
			Activity_Type::create($insert);
		}
	}

}