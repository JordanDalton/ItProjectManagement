<?php

class Home_Controller extends Base_Controller {

	/**
	 * Application Startpage
	 */
	public function get_index()
	{
		// Set the page content
		$this->layout->title = 'Startpage';
		$this->layout->content = View::make('home.index');
	}

	/**
	 * Login Page
	 */
	public function get_login()
	{
        if(Auth::check()) return Redirect::to_route('projects');

		// Set the page content
		$this->layout->title = "Log in";
		$this->layout->content = View::make('home.login')->nest('form', 'home.partials.forms.login');
	}

	/**
	 * Login page (POSTBACK)
	 */
	public function post_login()
	{
        // If user is already logged in, redirect to dashboard.
        if (Auth::user()) return Redirect::to_route('projects');
        
        // Create new instance of User
        $user = new User;
                
        // Check if form inputs pass validaton.
        if( $user->validate(Input::all(), 'login') )
        {
            // Attempt to validate ldap user credentials
            try { Auth::attempt( Input::all() ); }

            // Exception handling
            catch (Exception $exc)
            {
                // Failed authentication...send back to the login
                return Redirect::back()->with('login_errors', true)->with_input();  
            }

            // Prepare our data for the session (and potential new user record).
            $data = array(
                'firstname' => Auth::user()->firstname,
                'lastname'  => Auth::user()->lastname,
                'name'      => Auth::user()->name,
                'email'     => Auth::user()->email,
                'username'  => Auth::user()->username
            );

            // Check if user already exists in our database
            $exists = $user->where_email(Auth::user()->email)->count();

            // User is a WRS Employee but this is their first time using the app.
            if( (bool) $exists == false )
            {        
                // Generate new user account.
                IoC::resolve('task', array('user'))->create($data);
            }

            // Loop through our data and place it in the sessions.
            foreach($data as $key => $value )
            {
                // Place the data in the session.
                Session::put($key, $value);   
            } 

            // User attempted to access specific URL before logging in
            if( Session::has('pre_login_url') )
            {
                // Fetch and assign the url from the session.
                $url = Session::get('pre_login_url');   
                
                // Remove the pre_login_url form the session data.
                Session::forget('pre_login_url');
                
                // Redirect user
                return Redirect::to($url);
            }

            // If login is valid then redirect to the dashboard.
            return Redirect::to_route('projects');
        }
        
        // Validation failed
        else
        {            
            // Return the user to the form, along with errors and original input.
            return Redirect::back()->with_errors($user->validation_errors())->with_input();
        }
	}

}