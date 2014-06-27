<?php
/**
 * Project Tasks
 *
 * @author Jordan Dalton <jordandalton@wrsgroup.com>
 */
class Project_Task
{
	public function run(){}

	/**
	 * Create Project Account
	 * @param array $data The data to be inserted.
	 * @return Project
	 */
	public function create($data = array())
	{
        // Create project object
        $newProject = new Project($data);

        // Now take the data and create a new record.
        $newProject->save();

        // New project event (for handling activity feed)
        //Event::fire('project.new');

        // Return object
        return $newProject;
	}

	/**
	 * Update Project Account
	 * @param  string|int $id   The identification number of the record to update.
	 * @param  array  $data 	The update data to be applied to the record.
	 * @return Project
	 */
	public function update($id, $data = array())
	{
		// Create user instance
		return Project::find($id)->fill($data)->save();
	}

	/**
	 * Delete Project Account (Soft-Delete)
	 * @param  string|int $id   The identification number of the record to update.
	 * @return Project
	 */
	public function delete($id)
	{
		return $this->update($id, array('deleted' => true));
	}
}