<?php

class Activity_Tag extends My_Eloquent
{
	/**
	 * The name of the table associated with the model.
	 *
	 * @var string
	 */
	public static $table = 'activities_tags';

	/**
	 * The relationships that should be eagerly loaded.
	 *
	 * @var array
	 */
	public $includes = array('activity');

	/**
	 * Return the activity the tag is related to.
	 * @return Activity
	 */
	public function activity()
	{
		return $this->belongs_to('Activity');
	}

	/**
	 * Return the associate record for the tag.
	 * @return Record
	 */
	public function record()
	{
		return $this->belongs_to($this->original['model'], 'id');
	}
}