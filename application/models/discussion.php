<?php
/**
 * Discussion Data Model
 *
 * @author Jordan Dalton <jordandalton@wrsgroup.com>
 */
class Discussion extends My_Eloquent
{
	/**
	 * The relationships that should be eagerly loaded.
	 *
	 * @var array
	 */
	public $includes = array('user');
	
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
    	// Create new discussion topic
        'create' => array(
            'title' => 'required',
            'post' 	=> 'required'
        ),
    );

	/**
	 * Feth the project the discussion is related to.
	 * @return Object
	 */
	public function project()
	{
		return $this->belongs_to('Project');
	}

	/**
	 * Fetch all the posts for the given discussion.
	 * @return Object
	 */
	public function posts()
	{
		return $this->has_many('Discussion_Post');
	}

	/**
	 * Fetch the user who made the post.
	 * @return Object
	 */
	public function user()
	{
		return $this->belongs_to('User');
	}
}