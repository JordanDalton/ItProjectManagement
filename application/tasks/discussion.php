<?php
/**
 * Discussion Tasks
 *
 * @author Jorda Dalton <jordandalton@wrsgroup.com>
 */
class Discussion_Task {

	public function run(){}

	/**
	 * [create description]
	 * @return [type] [description]
	 */
	public function create($data = array())
	{
		// Create new discussion instance
		$newDiscussion = new Discussion( $data );

		// Save the record
		$newDiscussion->save();

        // New user event (for handling activity feed)
        //Event::fire('discussion.new');

		return $newDiscussion;
	}
		
	/**
	 * [create description]
	 * @return [type] [description]
	 */	
	public function update($id, $data = array())
	{
		return Discussion::find($id)->fill($data)->save();
	}
	
	/**
	 * [create description]
	 * @return [type] [description]
	 */	
	public function delete($id)
	{
		return $this->update($id, array('deleted' => true));
	}
}