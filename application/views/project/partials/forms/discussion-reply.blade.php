<?php

    // Open the corm
    echo Form::open(URL::to_route('project.discussion.post.create', $discussionId).$query_string, 'POST', array('class' => 'well'));
                
        // Check for login errors flash var
        if( Session::has('login_errors') ):

            echo '<div id="login-error" class="alert alert-error">' .
                    '<strong>Oops!</strong> Looks like there was a problem with your submission.'.
                 '</div>';
        endif;
  
        // Project description control group
        echo Form::control_group(
                Form::label('post', 'Post Reply'),
                Form::textarea('post', Input::old('post'), array('style' => 'width:98%', 'rows' => 6)), $errors->has('post') ? 'error ' : '',
                $errors->has('post') ? Form::block_help( $errors->first('post') ) : ''
            );
  
        
        // Form Buttons
        echo Form::actions(array(
                Form::submit('Post Reply', array('class' => 'btn btn-primary btn')),
        ));
        
    // Close the form
    echo Form::close();