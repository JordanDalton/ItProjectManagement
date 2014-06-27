<?php

class Activity extends My_Eloquent
{
	/**
	 * The relationships that should be eagerly loaded.
	 *
	 * @var array
	 */
	public $includes = array('type', 'tags');

	/**
	 * Fetch the associate record.
	 * @return Relationship
	 */
	public function record()
	{
		// Fetch the record model type
		$model = $this->type->model;
		
		// Return the relationship
		return $this->belongs_to($model);
	}

	/**
	 * Return the user involved with the activity
	 * 
	 * @return User
	 */
	public function tags()
	{
		return $this->has_many('Activity_Tag', 'id', Activity_Tag::$table);
	}

	/**
	 * Return the related activity type.
	 * 
	 * @return Activity_Type
	 */
	public function type()
	{
		return $this->belongs_to('Activity_Type', 'activity_type_id');
	}
}