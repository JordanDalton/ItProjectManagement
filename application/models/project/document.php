<?php
/**
 * Project Document Model
 *
 * @author Jordan Dalton <jordandalton@wrsgroup.com>
 */
class Project_Document extends My_Eloquent
{
	/**
	 * The name of the table associated with the model.
	 *
	 * @var string
	 */
	public static $table = 'projects_documents';

	/**
	 * Project the document is related to.
	 * @return Object
	 */
	public function project()
	{
		return $this->belongs_to('Project');
	}

	/**
	 * User that submitted the document.
	 * @return Object
	 */
	public function user()
	{
		return $this->belongs_to('User');
	}
}