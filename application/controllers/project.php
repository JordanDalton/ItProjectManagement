<?php
/**
 * Project Controller
 *
 * @author Jordan Dalton <jordandalton@wrsgroup.com>
 */
class Project_Controller extends Base_Controller
{
	/**
	 * Show all projects.
	 */
	public function get_index($category = false)
	{
		// Fetch all the project category name from cache.
		$available_categories = IoC::resolve('project_categories_list');

		// Automatically redirect to primary category if $category is not set.
		if(!$category) return Redirect::to_route('projects', Str::lower($available_categories[0]->name));

		// Limit the number of results.
		$limit = Input::query('limit', 10);

		// Set the query string appends
		$appends = array(
			'category'	=> $category,
			'limit' 	=> $limit
		);

		// Capture the project category information
		$project_category = Project_Category::where_name($category)->first();

		// Fetch projects from the database
		$projects = $project_category->projects()->where_deleted(0)->order_by('id')->paginate( $limit );

		// Set the page content
		$this->layout->title = 'Projects';
		$this->layout->content = View::make('project.index')
									->with('project_category', $project_category)
									->with('projects', $projects)
									->with('appends', $appends)
									->with('available_categories', $available_categories);
	}

	/**
	 * Create new project.
	 */
	public function get_create()
	{
		// Set the page content
		$this->layout->title = 'Create Project';
		$this->layout->content = View::make('project.create')
									 ->nest('form', 'project.partials.forms.create-project');
	}

	/**
	 * Create new project. (POSTBACK)
	 */
	public function post_create()
	{        
        // Create new instance of User
        $project = new Project;
                
        // Check if form inputs pass validaton.
        if( $project->validate( Input::all(), 'create') )
        {
        	// Data to be inserted
        	$data = array();

        	// Loop through the data
        	foreach(Input::all() as $key => $value)
        	{
        		// Sanitize all data.
        		$data[$key] = Helper::sanitize($value);
        	}

            // Create project object
            $newProject = My_Auth::user()->projects()->insert( Input::all() );

            // New project event.
            Event::fire('project.new', array($newProject));

            // If login is valid then redirect to the dashboard.
            return Redirect::to_route('project.profile', $newProject->id);
        }
        
        // Validation failed
        else
        {            
            // Return the user to the form, along with errors and original input.
            return Redirect::back()->with_errors($project->validation_errors())->with_input();
        }
	}

	/**
	 * Create new project discussion.
	 * @param  string|int $id The identification number of the project.
	 */
	public function get_create_discussion($id)
	{
		// Fetch the project record
		$project = Project::find(1);

		// Set the page content
		$this->layout->title = 'Create Discussion Topic';
		$this->layout->content = View::make('project.create-discussion')
									->with('project', $project)
									->nest('form', 'project.partials.forms.create-project-discussion');
	}

	/**
	 * Create new project discussion (POSTBACK)
	 * @param  string|int $id The identification number of the project.
	 * @return Redirect
	 */
	public function post_create_discussion($id)
	{
        // Create new instance of User
        $discussion = new Discussion;
                
        // Check if form inputs pass validaton.
        if( $discussion->validate( Input::all(), 'create') )
        {
        	// Data to be inserted
        	$data = array();

        	// Loop through the data
        	foreach(Input::all() as $key => $value)
        	{
        		// Sanitize all data.
        		$data[$key] = $value;// e( strip_tags($value) );
        	}

        	// Capture the user's id number
        	$user_id = My_Auth::user()->id;

        	// Create new discussion record in the database
			$newDiscussion = Project::find($id)->discussions()->insert(array(
				'title' 	=> $data['title'],
				'user_id' 	=> $user_id
			));

			// Create initial post for the new discussion
			$newPost = $newDiscussion->posts()->insert(array(
				'post' 		=> $data['post'],
				'user_id' 	=> $user_id
			));

            // New discussion event.
            Event::fire('discussion.new', array($newDiscussion));
            
            // If login is valid then redirect to the dashboard.
            return Redirect::to_route('project.discussion', $newDiscussion->id);
        }
        
        // Validation failed
        else
        {            
            // Return the user to the form, along with errors and original input.
            return Redirect::back()->with_errors($discussion->validation_errors())->with_input();
        }
	}

	/**
	 * Project Discussions Board
	 * @param  string|int $id The project identification number.
	 */
	public function get_discussions($id)
	{
		// Limit the number of results.
		$limit = Input::query('limit', 10);

		// Set the query string appends
		$appends = array(
			'limit' => $limit
		);

		// Fetch the project record
		$project = Project::find( $id );

		// If the project doesn't exist...bail!
		if(! $project ) return Redirect::to_route('projects');

		// Fetch discussions for a particular project.
		$discussions = $project->discussions()
							   ->where_deleted(0)
							   ->order_by('updated_at', 'desc')
							   ->paginate( $limit );

		// Set the page content
		$this->layout->title = 'Project Discussions';
		$this->layout->content = View::make('project.discussions')
									->with('appends', $appends)
									->with('discussions', $discussions)
									->with('project', $project);
	}

	/**
	 * View project discussion.
	 * @param  string|int $id The identification number of the discussion.
	 */
	public function get_discussion($id)
	{
		$qString = '?';
		$query_string = Input::query();
		foreach($query_string as $key => $value)
		{
			$qString .= $key . '=' . $value . '&';
		}

		// Fetch the discussion record.
		$discussion = Discussion::find($id);

		// Fetch the posts for the discussion
		$posts = $discussion->posts()->paginate($discussion::$per_page);

		// Set the page content
		$this->layout->title = $discussion->title;
		$this->layout->content = View::make('project.discussion')
									->with('discussion', $discussion)
									->with('posts', $posts)
									->nest('form', 'project.partials.forms.discussion-reply', array('discussionId' => $discussion->id, 'query_string' => $qString));
	}

	/**
	 * Create post on discussion.
	 * @param  string|int $id The identification number of the discussion.
	 */
	public function post_create_discussion_post($id)
	{
        // Create new instance of User
        $discussionPost = new Discussion_Post;
                
        // Check if form inputs pass validaton.
        if( $discussionPost->validate( Input::except('page'), 'create') )
        {
        	// Data to be inserted
        	$data = array();

        	// Loop through the data
        	foreach(Input::except('page') as $key => $value)
        	{
        		// Sanitize all data.
        		$data[$key] = $value;// e( strip_tags($value) );
        	}

        	// Capture the user's id number
        	$user_id = My_Auth::user()->id;

        	// Add the user id to the input array.
        	Input::merge(array('user_id' => $user_id));

        	// Fetch the discussion record
        	$discussion = Discussion::find($id);

        	// Update the updated_at field of the dicussion topic
        	$discussion->touch();

        	// Create new discussion post record in the database
			$post = $discussion->posts()->insert(Input::except('page'));

            // New post event event.
            Event::fire('post.new', array($post));

			// Redirect user to their post
			return Redirect::to_route('project.discussion.post', $post->id);
        }
        
        // Validation failed
        else
        {            
            // Return the user to the form, along with errors and original input.
            return Redirect::back()->with_errors($discussionPost->validation_errors())->with_input();
        }
	}

	/**
	 * Project Documents
	 * @param  string|int $id The project identification number.
	 */
	public function get_documents($id)
	{
		// Fetch the project record
		$project = Project::find($id);

		// Fetch the documents assigned to this project.
		$documents = $project->documents()->where(function($where){
			$where->where_deleted(0);
		})->get();

		// Set the page content
		$this->layout->title = 'Project documents';
		$this->layout->content = View::make('project.documents')->with('project', $project)->with('documents', $documents);
	}

	/**
	 * Upload document to the project.
	 * @param  string|int $id The identification number of the project.	
	 * @return View
	 */
	public function get_create_document($id)
	{
		// Fetch the project record
		$project = Project::find($id);

		// Set the page content
		$this->layout->title = 'Upload Document';
		$this->layout->content = View::make('project.create-document')->with('project', $project)->nest('form', 'project.partials.forms.create-document');
	}

	/**
	 * File was uploaded
	 * @param  string|int $id The identification number of the project.	
	 * @return [type] [description]
	 */
	public function post_create_document($id)
	{
		// Get the files
		$files = Input::file('docFiles');

		// Pepare array that will contain new inputs
		$newInputs = array();

		// Get the file count
		$filesCount = count($files['name']);

		$counter = 0;
		foreach($files['name'] as $file)
		{
			// Prepare data
			$data = array(
				'name' 		=> $files['name'][$counter],
				'type' 		=> $files['type'][$counter],
				'tmp_name' 	=> $files['tmp_name'][$counter],
				'error' 	=> $files['error'][$counter],
				'size' 		=> $files['size'][$counter],
			);

			// Prepare new document record
			$document = Project::find($id)->documents()->insert(array(
				'filename'=> '',
				'user_id' => My_Auth::user()->id
			));
			// Save record into the database
			$document->save();

			// Update the newly-created filename.
			$document->filename = "{$document->id}_{$id}_{$data['name']}";

			// Now save again..
			$document->save();

			// New Document Event
			Event::fire('document.new', array($document));

			// Get the files
			$foundation = Request::foundation()->files->get('docFiles');

			// Upload the file
			$foundation[$counter]->move(path('app') . 'docs', $document->filename);

			// Increment the count
			$counter++;
		}
		
		// Now redirect back to the profile documents page.
		return Redirect::to_route('project.documents', $id);
	}

	/**
	 * Project Members
	 * @param  string|int $id The project identification number.
	 */
	public function get_members($id)
	{
		// Fetch the project record
		$project = Project::with('members')->find($id);

		// Fetch the members assigned to this project.
		$members = $project->members()->order_by('leader', 'desc')->get();

		// Is the current user a leader of the project?
		$isLeader = $project->isLeader( My_Auth::user()->id );

		// Set the page content
		$this->layout->title = 'Project Members';
		$this->layout->content = View::make('project.members')->with('project', $project)->with('members', $members)->with('isLeader', $isLeader);
	}

	/**
	 * Assign members to project page.
	 * @param  string|int $id The record identificaiton number of the project.
	 */
	public function get_members_assign($id)
	{
		// Fetch the project record
		$project = Project::find($id);

		// Set the page content
		$this->layout->title = 'Assign Members to Project';
		$this->layout->content = View::make('project.members-assign')->with('project', $project);
	}

	/**
	 * Project Profile Page
	 * @param  string|int $id The identification number for the project.
	 */
	public function get_profile($id)
	{
		// Fet the project record
		$project = Project::find($id);

		// Bail if the project doesn't exist.
		if(!$project) return Response::error(404);

		// Fetch the project discussions
		$discussions = $project->discussions()->order_by('updated_at', 'desc')->take(10)->get();

		// Fetch the project documents.
		$documents = $project->documents()->order_by('updated_at', 'desc')->take(10)->get();

		// Fetch the project member list
		$members = $project->members()->order_by('leader', 'desc')->get();

		// Set the page content
		$this->layout->title = $project->name;
		$this->layout->content = View::make('project.profile')
									 ->with('discussions', $discussions)
									 ->with('documents', $documents)
									 ->with('members', $members)
									 ->with('project', $project);
	}

	/**
	 * Update new project.
	 */
	public function get_update($id)
	{
		// Fetch the project record
		$project = Project::find($id);

		// Set the page content
		$this->layout->title = 'Update Project';
		$this->layout->content = View::make('project.update')
									 ->with('project', $project)
									 ->nest('form', 'project.partials.forms.update-project', array('project' => $project));
	}

	/**
	 * Update new project. (POSTBACK)
	 */
	public function post_update($id)
	{
        // Create new instance of User
        $project = new Project;
                
        // Check if form inputs pass validaton.
        if( $project->validate( Input::all(), 'create') )
        {
        	// Prepare the update data.
        	$data = array();

        	foreach(Input::all() as $key => $value)
        	{
        		// Auto-escape the data
        		$data[$key] = Helper::sanitize($value);
        	}

        	// Update the project record.
        	Project::find($id)->fill( $data )->save();

            // If login is valid then redirect to the dashboard.
            return Redirect::to_route('project.profile', $id);
        }
        
        // Validation failed
        else
        {            
            // Return the user to the form, along with errors and original input.
            return Redirect::back()->with_errors($project->validation_errors())->with_input();
        }
	}
}