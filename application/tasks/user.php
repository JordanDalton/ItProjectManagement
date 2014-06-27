<?php
/**
 * User Task
 *
 * @author Jordan Dalton <jordandalton@wrsgroup.com>
 */
class User_Task {

	public function run(){}

	/**
	 * Create User Account
	 * @return [type] [description]
	 */
	public function create($data = array())
	{
        // Create user object
        $newUser = new User($data);

        // Now take the data and create a new record.
        $newUser->save();

        // New user event (for handling activity feed)
        Event::fire('user.new', array($newUser));

        // Return object
        return $newUser;
	}

	/**
	 * Update User Account
	 * @param  [type] $id   [description]
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public function update($id, $data = array())
	{
		// Create user instance
		return User::find($id)->fill($data)->save();
	}

	/**
	 * Delete User Account (Soft-Delete)
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function delete($id)
	{
		return $this->update($id, array('deleted' => true));
	}
}