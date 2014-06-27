<?php

class Activity_Type extends My_Eloquent
{
	/**
	 * The name of the table associated with the model.
	 *
	 * @var string
	 */
	public static $table = 'activities_types';

	/**
	 * Fetch all activites for a particular activity type.
	 * @return Activity
	 */
	public function activities()
	{
		return $this->has_many('Activity');
	}
}