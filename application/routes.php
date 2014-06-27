<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
*/

/**
 * Application Startpage
 */
Route::get('/', array(
	'as'	=> 'home',
	function(){
		// Automatically redirect to the login page.
		return Redirect::to_route('login');
	}
));

/**
 * User Login
 */
Route::get('/login', array(
	'as'	=> 'login',
	'uses'	=> 'home@login'
));

Route::post('/login', array(
	'uses'	=> 'home@login'
));

Route::get('/logout', array(
	'as'	 => 'logout',
	function(){

		// Log the user out of their sesssion.
		Auth::logout();

		// Redirect to login page.
		return Redirect::to_route('login');
	}
));


/**
 * Activities
 */
Route::get('/activities', array(
	'as'	=> 'activities',
	'uses'	=> 'activity@index'
));

Route::get('/activities/user/profile/(:num)', array(
	'as'	=> 'activities.user.profile',
	'uses'	=> 'activity.user@profile'
));

Route::get('/activities/project/profile/(:num)', array(
	'as'	=> 'activities.project.profile',
	'uses'	=> 'activity.project@profile'
));

/**
 * Ajax
 */
Route::get('/ajax/ldap-search/(:num)', array(
	'before'=> 'auth|is_leader',
	'uses'	=> 'ajax@search_ldap'
));

Route::get('/ajax/assign-project-member/(:num)', array(
	'before'=> 'auth|is_leader',
	'uses'	=> 'ajax@assign_project_member'
));

Route::get('/ajax/remove-project-member/(:num)', array(
	'before'=> 'auth|is_leader',
	'uses'	=> 'ajax@remove_project_member'
));

/**
 * Documents
 */
Route::get('/documents/(:any)', array(
	'as'	=> 'document.profile',
	'before'=> 'auth|referring_url',
	function($filename){
		// Separate by underscore
		$explode = explode('_', $filename);

		// A record id and project id are required to continue.
		if( !isSet($explode[0]) && !isSet($explode[1]) ) return 'The filename you provided is invalid.';

		// Fetch file record
		$document = Project_Document::where(function($where) use($explode, $filename){
			$where->where_id($explode[0]);
			$where->where_project_id($explode[1]);
			$where->where_filename($filename);
		})->first();

		// Document record exists
		if( isSet($document->exists) )
		{
			// The path to the file
			$filePath = path('app') . 'docs/' . $document->filename;

			// File Exists
			if( File::exists( $filePath ) ){
				$extension 	= File::extension( $document->filename );	// File Extension
				$mime 		= File::mime( $extension ); 				// MIME TYPE
				$content 	= File::get( $filePath );					// Content

				// Force file download
				return Response::download($filePath, time() . '_' . $document->filename, array('Content-Type' => $mime));
			}
			// File is unreachable (doesn't exist)...alert the user.
			else return 'Database record exists, but the file is unreachable.';
		}
		// Alert the user that the file specified doesn't exist.
		else return 'The file you\'ve requested does not exist.';
	}
));

/**
 * Projects
 */
Route::get('/projects/(:any?)', array(
	'as'	=> 'projects',
	'before'=> 'auth',
	'uses'	=> 'project@index'
));

Route::get('/project/create', array(
	'as'	=> 'project.create',
	'before'=> 'auth',
	'uses'	=> 'project@create'
));

Route::post('/project/create', array(
	'before'=> 'auth',
	'uses'	=> 'project@create'
));

Route::get('/project/create/document/(:num)', array(
	'as'=> 'project.document.create',
	'before'=> 'auth|is_leader:4',
	'uses'	=> 'project@create_document'
));

Route::post('/project/create/document/(:num)', array(
	'before'=> 'auth|is_leader:4',
	'uses'	=> 'project@create_document'
));

Route::get('/project/create/discussion/(:num)', array(
	'as'=> 'project.discussion.create',
	'before'=> 'auth',
	'uses'	=> 'project@create_discussion'
));

Route::post('/project/create/discussion/(:num)', array(
	'before'=> 'auth',
	'uses'	=> 'project@create_discussion'
));

Route::post('/project/create/discussion-post/(:num)', array(
	'as'	=> 'project.discussion.post.create',
	'before'=> 'auth|referring_url',
	'uses'	=> 'project@create_discussion_post'
));

Route::get('/project/discussions/(:num)', array(
	'as'	=> 'project.discussions',
	'before'=> 'auth',
	'uses'	=> 'project@discussions'
));

Route::get('/project/discussion/(:num)', array(
	'as'	=> 'project.discussion',
	'before'=> 'auth',
	'uses'	=> 'project@discussion'
));

Route::get('/project/discussion-post/(:num)', array(
	'as'	=> 'project.discussion.post',
	function($post_id){

		// Records per page
		$per_page = Discussion::$per_page;

		// Fetch the post record
		$post_record = Discussion_Post::find($post_id);

		// Fetch the parent discussion topic
		$discussion = $post_record->discussion;

		// Set the default page number
		$page = 1;

		// Post Counter
		$counter = 0;

		// Start looping
		foreach($discussion->posts as $post)
		{
			$counter++; // Increment the counter

			if ( $post->id == $post_id )
			{
				$set_page = $counter / $per_page;
				$page = ceil($set_page);
				break;
			}
		}

		$query = http_build_query(array(
			'page' => $page
		));

		// Append Hash
		$queryWithHash = $query . '#' . $post_id;

		// Link
		$link = URL::to_route('project.discussion', $discussion->id) . '?' . $queryWithHash;

		// Now redirect the person to the appropriate page.
		return Redirect::to($link);
	}
));

Route::get('/project/documents/(:num)', array(
	'as'	=> 'project.documents',
	'before'=> 'auth',
	'uses'	=> 'project@documents'
));

Route::get('/project/assign/members/(:num)', array(
	'as'	=> 'project.members.assign',
	'before'=> 'auth|is_leader:4',
	'uses'	=> 'project@members_assign'
));

Route::get('/project/members/(:num)', array(
	'as'	=> 'project.members',
	'before'=> 'auth',
	'uses'	=> 'project@members'
));

Route::get('/project/profile/(:num)', array(
	'as'	=> 'project.profile',
	'before'=> 'auth',
	'uses'	=> 'project@profile'
));

Route::get('/project/update/(:num)', array(
	'as'	=> 'project.update',
	'before'=> 'auth|is_leader',
	'uses'	=> 'project@update'
));

Route::post('/project/update/(:num)', array(
	'before'=> 'auth|is_leader',
	'uses'	=> 'project@update'
));

/**
 * Users
 */
Route::get('/user/avatar', array(
	'as'	=> 'user.avatar',
	'before'=> 'auth|referring_url',
	'uses'	=> 'user@avatar'
));

Route::post('/user/avatar', array(
	'before'=> 'auth',
	'uses'	=> 'user@avatar'
));


Route::get('/user/profile/(:num)', array(
	'as'	=> 'user.profile',
	'before'=> 'auth',
	'uses'	=> 'user@profile'
));

Route::get('/user/projects/(:num)/(:any?)', array(
	'as'	=> 'user.projects',
	'before'=> 'auth',
	'uses'	=> 'user@projects'
));

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});


/**
 * New User Event
 */
Event::listen('user.new', function($object){

	// Generate Activity Record
    $activity = Activity_Type::where_name('user.new')
        ->first()
        ->activities()
        ->insert(array(
            'record_id' => $object->id,
    ));

    // Tag the user to the activity
    Activity_Tag::create(array(
        'activity_id'   => $activity->id,
        'record_id'     => $activity->record_id,
        'model'         => 'User',
    ), true);
});

/**
 * New Project Event
 */
Event::listen('project.new', function($object){

	// Generate Activity Record
    $activity = Activity_Type::where_name('project.new')
        ->first()
        ->activities()
        ->insert(array(
            'record_id' => $object->id,
    ));

    // Tag to the project
    Activity_Tag::create(array(
        'activity_id'   => $activity->id,
        'record_id'     => $activity->record_id,
        'model'         => 'Project',
    ), true);

    // Tag to the user
    Activity_Tag::create(array(
        'activity_id'   => $activity->id,
        'record_id'     => My_Auth::user()->id,
        'model'         => 'User',
    ), true);

    // Update counts for the parenting project
    Event::fire('project.update.counts', array($object));
});

/**
 * Updated Project Event
 */
Event::listen('project.update.counts', function($id){

	// Update Counts
	$project = Project::find(1);
	$project->discussion_count 	= $project->discussions()->count();
	$project->document_count 	= $project->documents()->count();
	$project->member_count 		= $project->members()->count();
	$project->save();

});

/**
 * Updated Discussion Event
 */
Event::listen('discussion.update.counts', function($id){

	// Update Counts
	$discussion = Discussion::find(1);
	$discussion->post_count = $discussion->posts()->count();
	$discussion->save();
});

/**
 * New Discussion Event
 */
Event::listen('discussion.new', function($object){

	// Generate Activity Record
    $activity = Activity_Type::where_name('discussion.new')
        ->first()
        ->activities()
        ->insert(array(
            'record_id' => $object->id,
    ));

    // Tag to the project
    Activity_Tag::create(array(
        'activity_id'   => $activity->id,
        'record_id'     => $activity->record_id,
        'model'         => 'Discussion',
    ), true);

    // Tag to the project
    Activity_Tag::create(array(
        'activity_id'   => $activity->id,
        'record_id'     => $activity->record_id,
        'model'         => 'Project',
    ), true);

    // Tag to the user
    Activity_Tag::create(array(
        'activity_id'   => $activity->id,
        'record_id'     => My_Auth::user()->id,
        'model'         => 'User',
    ), true);

    // Update counts for the parenting project
    Event::fire('project.update.counts', array($object->project));

});

/**
 * New Post Event
 */
Event::listen('post.new', function($object){

	// Generate Activity Record
    $activity = Activity_Type::where_name('post.new')
        ->first()
        ->activities()
        ->insert(array(
            'record_id' => $object->id,
    ));

    // Tag to the project
    Activity_Tag::create(array(
        'activity_id'   => $activity->id,
        'record_id'     => $object->discussion->id,
        'model'         => 'Discussion',
    ), true);

    // Tag to the project
    Activity_Tag::create(array(
        'activity_id'   => $activity->id,
        'record_id'     => $object->discussion->project->id,
        'model'         => 'Project',
    ), true);

    // Tag to the user
    Activity_Tag::create(array(
        'activity_id'   => $activity->id,
        'record_id'     => My_Auth::user()->id,
        'model'         => 'User',
    ), true);

    // Update counts for the parenting project
    Event::fire('project.update.counts', array($object->discussion->project));

});

/**
 * New Document Event
 */
Event::listen('document.new', function($object){

	// Generate Activity Record
    $activity = Activity_Type::where_name('document.new')
        ->first()
        ->activities()
        ->insert(array(
            'record_id' => $object->id,
    ));

    // Tag to the project
    Activity_Tag::create(array(
        'activity_id'   => $activity->id,
        'record_id'     => $object->project_id,
        'model'         => 'Project',
    ), true);

    // Tag to the user
    Activity_Tag::create(array(
        'activity_id'   => $activity->id,
        'record_id'     => My_Auth::user()->id,
        'model'         => 'User',
    ), true);

    // Update counts for the parenting project
    Event::fire('project.update.counts', array($object->project));

});

/**
 * Access Denied Event
 */
Event::listen('access.denied', function($user, $url, $ip)
{
	Log::write('info', "Access Denied - {$user->name} ({$ip}) was denied access to the following link: {$url}");
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{	
	if (Auth::driver('ldapauth')->guest())
	{
		// Save the attempted URL
		Session::put('pre_login_url', URL::current());

		// Redirect to login
		return Redirect::to('login');
	}
});

Route::filter('is_leader', function($segment = 3){

	// Capture the project id from the uri.
	$project_id = URI::segment($segment, 3);

	// Fetch the project record
	$project = Project::find( $project_id );

	// Project Record Exists
	if($project)
	{
		// See if the user is aleader of the project.
		$isLeader = $project->isLeader( My_Auth::user()->id );

		// If not a leader...bail!
		if( ! $isLeader )
		{
			// Log this attempt
			Event::fire('access.denied', array(My_Auth::user(), URL::current(), Request::ip()));

			// Is ajax request
			if( Request::ajax() )
			{
				return Response::json(array('error' => 1, 'message' => 'Action Denied (You don\'t have permission to modify this record)'));
			}

			return Redirect::to_route('project.profile', $project_id)->with('permission_error', true)->with('permission_error_url', URL::current());
		}
	} 

	// Project doesn't exist.
	else 
	{
		// Is ajax request
		if( Request::ajax() )
		{
			return Response::json(array('error' => 1, 'message' => 'The project you\'ve requested does not exist.'));
		}

		// Redirect to the projects page.
		return Redirect::to_route('projects')->with('projectNonExistent', true);;
	}
});

Route::filter('referring_url', function(){
	Session::put('referring_url', Request::referrer());
});