<?php

class Activity_Controller extends Controller
{
	/**
	 * Indicates if the controller uses RESTful routing.
	 *
	 * @var bool
	 */
	public $restful = true;

	/**
	 * Fetch activit(y|ies)
	 * 
	 * @param  boolean|string|int $id The identification id for the activity record.
	 * @return Response
	 */
	public function get_index($id = false)
	{
		// Fetch the record(s) from the database.
		//$activities = !$id ? Activity::all() : Activity::with('type')->find($id);

		$activities = Activity::where(function($where) use($id){
			if($id) $where->where_id($id);
		})->order_by('created_at', 'desc')->take(10)->get();

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
}