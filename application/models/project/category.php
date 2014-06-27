<?php

class Project_Category extends Eloquent
{
	/**
	 * The name of the table associated with the model.
	 *
	 * @var string
	 */
	public static $table = 'projects_categories';

	/**
	 * Return the projects that are assigned to the category/
	 * @return Project
	 */
	public function projects()
	{
		return $this->has_many('Project');
	}
}