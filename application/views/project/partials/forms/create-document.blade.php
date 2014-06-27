<br/>
<?php

	// Open the form
	echo Form::open_for_files(null, 'POST', array('class' => 'well'));

    // Project name control group
    echo Form::control_group(
            Form::label('file', 'Select the file(s) you wish to upload.'),
            Form::file('docFiles[]', array('class' => 'multi')), $errors->has('file') ? 'error ' : '',
            $errors->has('file') ? Form::block_help( $errors->first('file') ) : ''
        );

    // Line Break
    echo('<hr/>');

    // Form Submission BUtton
	echo Form::submit('Upload File(s)');

	// Close the form
	Form::close();