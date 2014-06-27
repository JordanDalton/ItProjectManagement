<?php
/**
 * User Data Model
 *
 * @author Jordan Dalton <jordandalton@wrsgroup.com>
 */
class User extends My_Eloquent
{
    /**
     * Global application administrators.
     * 
     * @var array
     */
    public static $administrators = array('jdalton', 'emorgan', 'whutyra', 'dfabianke');
    
    /**
     * Validation Rules
     * 
     * @var array
     */
    public $validation_rules = array(
        'create' => array(
            'email'     => 'required|email|unique:users',
            'first_name'=> 'required|alpha_dash',
            'last_name' => 'required|alpha_dash',
            'password'  => 'required|alpha_dash|min:4',
            'username'  => 'required|alpha_dash|unique:users'
        ),
        'update' => array(
            'email'     => 'required|email',
            'first_name'=> 'required|alpha_dash',
            'last_name' => 'required|alpha_dash',
            'password'  => 'alpha_dash|min:4',
            'username'  => 'required|alpha_dash'
        ),
        'login' => array(
            'username' => 'required|alpha_dash',
            'password' => 'required'
        )
    );
    
    /**
     * Validation Messages
     * 
     * @var array
     */
    public $validation_messages = array(
        'login' => array(
            'username_exists' => 'Sorry, that :attribute does not exist.',
        ),
    );

    /**
     * Return the activities related to the project.
     * @return Activity
     */
    public function activities()
    {
        return $this->has_many_and_belongs_to('Activity', 'activities_tags', 'record_id')
                    ->where('activities_tags.model', '=', 'User');
    }
	
    /**
     * Check if specified username is a global administrator.
     * @param  string  $username The username to be checked against.
     * @return boolean           True = Match, False = No Match
     */
    public static function isAdmin($username)
    {
        return in_array($username, self::$administrators);
    }

	/**
	 * Fetch all the projects created by the user.
	 * @return Object
	 */
	public function projects()
	{
		return $this->has_many('Project');
	}

    /**
     * Fetch projects the user is a member of.
     * @return Object
     */
    public function projects_users()
    {
        return $this->has_many('Project_User', 'user_id');
    }

	/**
	 * Fetch all discussions that originated from the user.
	 * @return Object
	 */
	public function discussions()
	{
		return $this->has_many('Discussion');
	}

	/**
	 * Fetch all posts that originated from the user.
	 * @return Object
	 */
	public function discussion_posts()
	{
		return $this->has_many('Discussion_Post');
	}
}