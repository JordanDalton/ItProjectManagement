<?php


HTML::macro('activity_feed', function(){

	foreach(Activity::all() as $activity)
	{
		$type 	= $activity->type;
		$record = $activity->record();
		$output = '';

		switch($type->name)
		{
			case 'project.new':
				$string = '<p style="font-size:.8em">%s created the following project:</p><p>%s</p>';
				$param[0] = HTML::link_to_route('user.profile', $record->user->name, $record->user->id);
				$param[1] = HTML::link_to_route('project.profile', $record->name, $record->id);
				$output .= sprintf($string, $param[0], $param[1]);
			break;
		}

		// Return the html output
		return $output;
	}
});