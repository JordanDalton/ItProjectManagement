<?php

class Activity_User_Controller extends Controller
{
	/**
	 * Indicates if the controller uses RESTful routing.
	 *
	 * @var bool
	 */
	public $restful = true;

	/**
	 * Return the activity feed for a project.
	 * 
	 * @param  string|int $id The identification number for the project.
	 * @return HTML
	 */
	public function get_profile($id)
	{
		// Fetch the project record along with its activities
		$user = User::find($id);

		$activities = $user->activities()->order_by('created_at', 'desc')->take(10)->get();

		// The output html
		$html = '';

		// Start looping through the results.
		foreach( $activities as $activity )
		{
			switch( $activity->type->name )
			{
				// New project was created
				case 'discussion.new':
					// Output format
					$format = '<div>';
					$format .= 	'<p style="color:#aaa;font-size:.8em;margin-bottom:0;">%s</p>';
					$format .= 	'<p style="color:#888;font-size:.8em">%s started the following discussion:</p>';
					$format .= 	'<p><blockquote><strong>%s</strong><p style="color:#666;font-size:.9em;line-height:1.5">%s</p></blockquote></p>';
					$format .= '</div>';
					$format .= '<hr/>';

					$html .= sprintf(
						$format, 
						Helper::age($activity->created_at, true),
						HTML::link_to_route('user.profile', $activity->record->user->name, $activity->record->user->id),
						HTML::link_to_route('project.discussion', $activity->record->title, $activity->record->id),
						Str::limit(strip_tags($activity->record->posts()->first()->post), 100, '...')
					);
				break;

				// New document was created
				case 'document.new':
					// Output format
					$format = '<div>';
					$format .= 	'<p style="color:#aaa;font-size:.8em;margin-bottom:0;">%s</p>';
					$format .= 	'<p style="color:#888;font-size:.8em">%s uploaded the following document:</p>';
					$format .= 	'<p><blockquote><i class="icon-file"></i> %s</p>';
					$format .= '</div>';
					$format .= '<hr/>';
					
					$html .= sprintf(
						$format, 
						Helper::age($activity->created_at, true),
						HTML::link_to_route('user.profile', $activity->record->user->name, $activity->record->user->id),
						HTML::link_to_route('document.profile', $activity->record->filename, $activity->record->filename)
					);
				break;

				// New project was created
				case 'post.new':

					// Output format
					$format = '<div>';
					$format .= 	'<p style="color:#aaa;font-size:.8em;margin-bottom:0;">%s</p>';
					$format .= 	'<p style="color:#888;font-size:.8em">%s posted the following <strong style="text-decoration:underline">%s</strong> to the <strong>%s</strong> discussion topic:</p>';
					$format .= 	'<p><blockquote>%s</blockquote></p>';
					$format .= '</div>';
					$format .= '<hr/>';

					$html .= sprintf(
						$format, 
						Helper::age($activity->created_at, true),
						HTML::link_to_route('user.profile', $activity->record->user->name, $activity->record->user->id),
						HTML::link_to_route('project.discussion.post', 'reply', array($activity->record->id)),
						HTML::link_to_route('project.discussion', $activity->record->discussion->title, $activity->record->discussion->id),
						Str::limit(strip_tags(preg_replace('/(<blockquote[^>]*?>([\s\S]*)<\/blockquote>)/', '', $activity->record->post)), 100, '...')
					);
				break;

				// New project was created
				case 'project.new':
					// Output format
					$format = '<div>';
					$format .= 	'<p style="color:#aaa;font-size:.8em;margin-bottom:0;">%s</p>';
					$format .= 	'<p style="color:#888;font-size:.8em">%s created the following project:</p>';
					$format .= 	'<p><blockquote><strong>%s</strong><p style="color:#666;font-size:.9em;line-height:1.5">%s</p></blockquote></p>';
					$format .= '</div>';
					$format .= '<hr/>';

					$html .= sprintf(
						$format, 
						Helper::age($activity->created_at, true),
						HTML::link_to_route('user.profile', $activity->record->user->name, $activity->record->user->id),
						HTML::link_to_route('project.profile', $activity->record->name, $activity->record->id),
						Str::limit(strip_tags($activity->record->description), 100, '...')
					);
				break;

				// New user was created
				case 'user.new':
					// Output format
					$format = '<div>';
					$format .= 	'<p style="color:#aaa;font-size:.8em;margin-bottom:0;">%s</p>';
					$format .= 	'<p style="color:#888">%s joined the website.</p>';
					$format .= '</div>';
					$format .= '<hr/>';

					$html .= sprintf(
						$format, 
						Helper::age($activity->created_at, true),
						HTML::link_to_route('user.profile', $activity->record->name, $activity->record->id)
					);
				break;
			}
		}

		// Return the output
		return $html;
	}

	/**
	 * Fech activity feed for a given user profile.
	 * @param  string|int $id The identification number of the users db record.
	 * @return Response
	 */
	public function get_profile_bu($id)
	{
		// Fetch of the activities the user was tagged in.
		$records = Activity_Tag::with('activity')->where(function($where) use($id){
			$where->where_model('User');
			$where->where_record_id($id);
		})->get();

		// The output html
		$html = '';

		echo('<div class="page-header"><h2>Activity<small>Feed</small></h2></div>');

		// Start looping through the records.
		foreach( $records as $record )
		{
			// Change the output based upon the event name
			switch( $record->activity->type->name )
			{
				// New user event
				case 'user.new':
					// Output format
					$format = '<div><strong>%s</strong> joined the website. %s</div>';
					$html  .= sprintf(
						$format, 
						HTML::link_to_route('user.profile', $record->activity->record->name, $record->activity->record_id),
						Helper::age($record->activity->record->created_at, true)
					);

				break;
			}
		}

		// Return the code
		return $html;
	}
}