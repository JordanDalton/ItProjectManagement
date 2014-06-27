<?php

    // Open the corm
    echo Form::open(null, 'POST', array('class' => ''));
                
        // Check for login errors flash var
        if( Session::has('login_errors') ):

            echo '<div id="login-error" class="alert alert-error">' .
                    '<strong>Oops!</strong> The username or password was incorrect.'.
                 '</div>';
        endif;

        $categories = array();
        foreach(IoC::resolve('project_categories_list') as $category)
        {
            $categories[$category->id] = $category->name;
        }

        // Project Category
        echo Form::control_group(
                Form::label('project_category_id', 'Project Category'),
                Form::select('project_category_id', $categories, array('selected' => Input::old('project_category_id', $project->project_category_id)))
        );
    
        // Project name control group
        echo Form::control_group(
                Form::label('name', 'Project Name'),
                Form::text('name', Input::old('name', $project->name), array('style' => 'width:96%')), $errors->has('name') ? 'error ' : '',
                $errors->has('name') ? Form::block_help( $errors->first('name') ) : ''
            );
      
        // Project description control group
        echo Form::control_group(
                Form::label('description', 'Project Description'),
                Form::textarea('description', Input::old('description', $project->description), array('style' => 'width:96%;')), $errors->has('description') ? 'error ' : '',
                $errors->has('description') ? Form::block_help( $errors->first('description') ) : ''
            );
  
        
        // Form Buttons
        echo Form::actions(array(
                Form::submit('Update Project', array('class' => 'btn btn-primary btn-large')),
        ));
        
    // Close the form
    echo Form::close();