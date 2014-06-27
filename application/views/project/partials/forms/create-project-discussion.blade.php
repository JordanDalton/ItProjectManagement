<?php

    // Open the corm
    echo Form::open(null, 'POST', array('class' => ''));
                
        // Check for login errors flash var
        if( Session::has('login_errors') ):

            echo '<div id="login-error" class="alert alert-error">' .
                    '<strong>Oops!</strong> Looks like there was a problem with your submission.'.
                 '</div>';
        endif;
    
        // Project name control group
        echo Form::control_group(
                Form::label('title', 'Discussion Title'),
                Form::text('title', Input::old('title'), array('style' => 'width:96%')), $errors->has('title') ? 'error ' : '',
                $errors->has('title') ? Form::block_help( $errors->first('title') ) : ''
            );
  
        // Project description control group
        echo Form::control_group(
                Form::label('post', 'Discussion Post'),
                Form::textarea('post', Input::old('post'), array('style' => 'width:96%')), $errors->has('post') ? 'error ' : '',
                $errors->has('post') ? Form::block_help( $errors->first('post') ) : ''
            );
  
        
        // Form Buttons
        echo Form::actions(array(
                Form::submit('Create Discussion Topic', array('class' => 'btn btn-primary btn-large')),
        ));
        
    // Close the form
    echo Form::close();