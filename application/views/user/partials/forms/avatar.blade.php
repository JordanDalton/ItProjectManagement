<?php

	// Open the form
	echo Form::open_for_files(null, 'POST');

	// File input 
	echo Form::file('picture');
	echo '<br/><br/>';
	// Submit Form Button
	echo Form::submit('Upload Photo');

	// Close the form
	echo Form::close();