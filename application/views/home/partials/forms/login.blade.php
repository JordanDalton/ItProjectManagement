<?php

    // Open the corm
    echo Form::horizontal_open(null, 'POST', array('class' => ''));
                
        // Check for login errors flash var
        if( Session::has('login_errors') ):

            echo '<div id="login-error" class="alert alert-error">' .
                    '<strong>Oops!</strong> The username or password was incorrect.'.
                 '</div>';
        endif;
    
        // Username control group
        echo Form::control_group(
                Form::label('username', 'Username'),
                Form::text('username', Input::old('username')), $errors->has('username') ? 'error ' : '',
                $errors->has('username') ? Form::block_help( $errors->first('username') ) : ''
            );

        // Password control group
        echo Form::control_group(
                Form::label('password', 'Password'),
                Form::password('password'), $errors->has('password') ? 'error ' : '',
                $errors->has('password') ? Form::block_help( $errors->first('password') ) : ''
        );    
        
        // Form Buttons
        echo Form::actions(array(
                Form::submit('Log me in!', array('class' => 'btn btn-primary')),
        ));
        
    // Close the form
    echo Form::close();
;?>