<?php

class Base_Ajax_Controller extends Controller
{
	/**
	 * Indicates if the controller uses RESTful routing.
	 *
	 * @var bool
	 */
	public $restful = true;

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}

	/**
	 * Create a new Controller instance.
	 *
	 * @return void
	 */
    public function __construct()
    {
        // Disable profiler for this entire bundle
        Event::override('laravel.done', function(){});
        
		parent::__construct();
    }
}