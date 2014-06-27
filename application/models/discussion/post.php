<?php
/**
 * Discussion Posts Data Model
 *
 * @author Jordan Dalton <jordandalton@wrsgroup.com>
 */
class Discussion_Post extends My_Eloquent
{
	/**
	 * The name of the table associated with the model.
	 *
	 * @var string
	 */
	public static $table = 'discussions_posts';

	/**
	 * The relationships that should be eagerly loaded.
	 *
	 * @var array
	 */
	public $includes = array('user');

    /**
     * Validation Rules
     * 
     * @var array
     */
    public $validation_rules = array(
        'create' => array(
            'post'     		=> 'required',
        ),
    );

	/**
	 * Fetch the discussion this post belongs to.
	 * @return Object 
	 */	
	public function discussion()
	{
		return $this->belongs_to('Discussion');
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