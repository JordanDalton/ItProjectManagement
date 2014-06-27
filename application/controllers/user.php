<?php

class User_Controller extends Base_Controller
{
	/**
	 * User avatar page.
	 * @param  string|int $id The user's identification number
	 */
	public function get_avatar()
	{
		// Set the page content
		$this->layout->title = 'User Avatar';
		$this->layout->content = View::make('user.avatar')->nest('form', 'user.partials.forms.avatar');
	}

	/**
	 * User avatar page. (POSTBACK)
	 * @param  string|int $id The user's identification number
	 */
	public function post_avatar()
	{
		// Fetch the user record
		$user = My_Auth::user();

		// Capture the post back image
	    $img = Input::file('picture');

	    // Save a thumbnail
	    $success = Resizer::open($img)
	    			->resize(64, 64, 'crop')
	    			->save('public/img/avatars/'.$user->id.'_64x64.jpg' , 99);

        // See if referring url was set.
        if( Session::has('referring_url') )
        {
            // Fetch and assign the url from the session.
            $url = Session::get('referring_url');   
            
            // Remove the pre_login_url form the session data.
            Session::forget('referring_url');
            
            // Redirect user
            return Redirect::to($url);
        }

		// Redirect user to their profile page.
	    return Redirect::to_route('user.profile', $user->id);
	}

	/**
	 * Show all users.
	 */
	public function get_index()
	{
		// Fetch users from the database
		$users = User::order_by('id')->paginate(10);

		// Set the page content
		$this->layout->title = 'Users';
		$this->layout->content = View::make('user.index')->with('users', $users);
	}

	/**
	 * User Profile Page
	 * @param  string|int $id The identification number for the user.
	 */
	public function get_profile($id)
	{
		// Fetch the user record from the db.
		$user = User::find($id);

		// If user doesn't exist...throw 404 error
		if( is_null($user) ) return Response::error('404');

		// Set the page content
		$this->layout->title = $user->name;
		$this->layout->content = View::make('user.profile')->with('user', $user);
	}

	/**
	 * Show projects for a particular user.
	 * @param  string|int $id The identification number of the user.
	 */
	public function get_projects($id, $category = false)
	{
		// Fetch all the project category name from cache.
		$available_categories = IoC::resolve('project_categories_list');

		// Automatically redirect to primary category if $category is not set.
		if(!$category) return Redirect::to_route('user.projects', array($id, Str::lower($available_categories[0]->name)));

		// Limit the number of results.
		$limit = Input::query('limit', 10);

		// Set the query string appends
		$appends = array(
			'limit' 	=> $limit
		);

		// Fetch the user record from the db.
		// -Also fetching the projects the user is a member of.
		$user = User::with('projects_users')->find($id);

		// Array that all project ids will be appended to.
		$projectIds = array();

		// Loop through all of the projects
		foreach($user->projects_users as $u)
		{
			// Assign the individual project id to our $projectIds list.
			$projectIds[] = $u->project_id;
		}

		// Create new instance of Project
		$project = new Project();

		// Prevent eager loading.
		$project->includes = array();

		// Fetch the projects
		//$projects =$project->where_in('id', $projectIds)->order_by('id')->paginate( $limit );
		$projects =$project->where(function($where) use($category, $projectIds){
			$where->where_in('id', (bool) $projectIds ? $projectIds : array(0));
			$where->where('project_category_id', '=', Project_Category::where('name', '=', $category)->first()->id);
		})->order_by('id')->paginate( $limit );

		// Project Category information
		$project_category = $category ? $category : '';

		// Set the page content
		$this->layout->title = '';
		$this->layout->content = View::make('user.projects')
									->with('appends', $appends)
									->with('available_categories', $available_categories)
									->with('projects', $projects)
									->with('project_category', $project_category)
									->with('user', $user);
	}
}