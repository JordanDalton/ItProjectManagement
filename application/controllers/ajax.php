<?php

class Ajax_Controller extends Base_Ajax_Controller
{
	/**
	 * Assign member to project
	 *
	 * @param string|int $id The id number of the project.
	 * @return Response
	 */
	public function get_assign_project_member($id)
	{
		// Prepare response object
		$response = new stdClass();
		$response->successful = false;

		// Fetch the name from the query string
		$name =  Input::query('name', '');

		// Fetch leadership flag from the query string
		$leader = Input::query('leader', 0);

		// First query for the user record
		$member = User::where_name( $name )->first();

		// Person is not currently a member of the site
		if( ! $member )
		{
			// Check user existence in ldap
			$ldap_results = My_Ldap::search( $name );

			// User exists
			if( isSet( $ldap_results[0] ) )
			{
	            // Prepare our data for the session (and potential new user record).
	            $data = array(
	                'firstname' => $ldap_results[0]->firstname,
	                'lastname'  => $ldap_results[0]->lastname,
	                'name'      => $ldap_results[0]->name,
	                'email'     => $ldap_results[0]->email,
	                'username'  => $ldap_results[0]->username
	            );

				// Create new user record
				$newUser = IoC::resolve('task', array('user'))->create($data);

				// Prepare the projects users data.
				$project_users_data = array(
					'leader'  => $leader,
					'user_id' => $newUser->id
				);

				// Fetch project record.
				$project = Project::find($id);

				// Create Member Record
				$newMember = $project->members()->insert($project_users_data);

				// Update response object.
				$response->successful = true;
			}

			// User doesn't exist.
			else
			{
				// Update response object.
				$response->successful = false;
			}
		}

		// User exists in the database
		else
		{
			// Prepare the projects users data.
			$project_users_data = array(
				'leader'  => $leader,
				'user_id' => $member->id
			);

			// Create Project Instance
			$project = Project::find($id);

			// Members of the project
			$members = $project->members();

			if( ! $members->where_user_id($member->id)->count() )
			{
				// Create the record
				$members->insert($project_users_data);

				// Update response status
				$response->successful = true;
			}
		}

		// Return the response.
		return Response::json($response);
	}

	/**
	 * Remove member from project.
	 *
	 * @param string|int $id The id number of the project.
	 * @return Response
	 */
	public function get_remove_project_member($id)
	{
		// Prepare response object
		$response = new stdClass();
		$response->successful = false;

		// Fetch the projet record
		$project = Project::find($id);

		// Project record exists
		if( $project )
		{
			// Capture the user id from the query string
			$user_id = Input::get('user_id');

			// User id is set
			if( $user_id )
			{
				// Remove the requested user from the project
				$response->successful = $project->members()->where_user_id($user_id)->delete();
			}
		}

		// Return the response
		return Response::json($response);
	}

	/**
	 * Search LDAP for an account based upon the persons name.
	 * @param  string $value The search criteria.
	 */
	public function get_search_ldap()
	{
		// Name to be searched
		$name = Input::query('name', '');

		// Return the results.
		return Response::json( My_Ldap::search( $name ) );
	}
}