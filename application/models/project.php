<?php
/**
 * Project Data Model
 *
 * @author Jordan Dalton <jordandalton@wrsgroup.com>
 */
class Project extends My_Eloquent
{
	/**
	 * The relationships that should be eagerly loaded.
	 *
	 * @var array
	 */
	public $includes = array('category');

	/**
	 * The default number of models to show per page when paginating.
	 *
	 * @var int
	 */
	public static $per_page = 10;

    /**
     * Validation Rules
     * 
     * @var array
     */
    public $validation_rules = array(
        'create' => array(
            'name'     		=> 'required',
            'description'	=> 'required',
        ),
        'update' => array(
            'name'     		=> 'required',
            'description'	=> 'required',
        ),
    );

    /**
     * Return the activities related to the project.
     * @return Activity
     */
    public function activities()
    {
    	return $this->has_many_and_belongs_to('Activity', 'activities_tags', 'record_id')
    				->where('activities_tags.model', '=', 'Project');
    }

    /**
     * Return the project category information.
     * @return Project_Category
     */
    public function category()
    {
    	return $this->belongs_to('Project_Category', 'project_category_id');
    }

	/**
	 * Fetch all the discussions for the project.
	 * @return Object
	 */
	public function discussions()
	{
		return $this->has_many('Discussion');
	}

	/**
	 * Count the number of discussions for a given project.
	 * @return integer
	 */
	public function discussionsCount()
	{
		return count($this->discussions);
	}

	/**
	 * Fetch all the documents for the project.
	 * @return Object
	 */
	public function documents()
	{
		return $this->has_many('Project_Document');
	}

	/**
	 * Check if particular user is a leader of the project
	 * @return Object
	 */
	public function isLeader($id = 0)
	{
		// Allow global admins access to anything
		if( in_array( My_Auth::user()->username, User::$administrators ) )
		{
			return TRUE;
		}

		return (bool) $this->members()->where(function($query) use($id){
			$query->where_leader(1);
			$query->where_user_id($id);
		})->count();
	}

	/**
	 * Feth all the project leaders for the project.
	 * @return Object
	 */
	public function leaders()
	{
		return $this->members()->where_leader(1)->get();
	}

	/**
	 * Members that are assigned to the project.
	 * @return Object
	 */
	public function members()
	{
		return $this->has_many('Project_User');
	}

	/**
	 * Fetch the user who submitted the project.
	 * @return Object
	 */
	public function user()
	{
		return $this->belongs_to('User');
	}
}