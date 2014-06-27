<?php

/**
 * User custom artisan task without artisan ;)
 * 
 * @var string $task_file The name of task you want to use.
 */
IoC::register('task', function( $task_file = '' ){
        
    // Full path to the task file.
    $task_path = path('app') . '/tasks/' . $task_file;

    // Convert dot syntax to slash
    $include_path = str_replace('.', '/', $task_path) . '.php';
    
    // Inclue the task file.
    include_once $include_path;
    
    // Set the task class name
    $task = str_replace('.', '_', $task_file). '_Task';

    return new $task;
});


/**
 * Fetch project category list from cache.
 */
IoC::register('project_categories_list', function(){

	// First, attempt to retrieve from cache, then from the database.
	return Cache::get('project_categories_list', function(){

		// Fetch all of the categories
		$categories = Project_Category::order_by('id')->get();

		// Write to cache memory
		Cache::forever('project_categories_list', $categories);

		// Now return the cateories
		return $categories;

		// Create an array for the results to be appended to
		$categories = array();

		// Query for all of the project categories from the database
		foreach(Project_Category::order_by('id')->get('name') as $category)
		{
			// Assign only the name to our array
			$categories[] = Str::lower($category->name);
		}

		// Write to cache memory
		Cache::forever('project_categories_list', $categories);

		// Now return the cateories
		return $categories;
	});
});

IoC::register('user_cache', function($username){

	return Cache::get($username . '_user_data', function() use($username){

		// Fetch the user data from the db
		$userData = User::where_username($username)->first();

		// Place the data into cache
		Cache::forever($username . '_user_data', $userData);

		// Return the data
		return $userData;
	});

});