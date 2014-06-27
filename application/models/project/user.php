<?php
/**
 * Project User Data Model
 *
 * @author Jordan Dalton <jordandalton@wrsgroup.com>
 */
class Project_User extends My_Eloquent
{

	/**
	 * The name of the table associated with the model.
	 *
	 * @var string
	 */
	public static $table = 'projects_users';
	
	/**
	 * The relationships that should be eagerly loaded.
	 *
	 * @var array
	 */
	public $includes = array('user');

	/**
	 * The project the member is a part of.
	 * @return Object
	 */
	public function project()
	{
		return $this->belongs_to('Project');
	}

	/**
	 * User cffe
	 * @return [type] [description]
	 */
	public function user()
	{
		return $this->belongs_to('User');
	}
}