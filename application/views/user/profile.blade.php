<section>
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<div class="media">
						<a href="#" class="pull-left" style="margin-top:2px">
							<img src="{{ Helper::avatar($user->id) }} " class="media-object img-polaroid">
						</a>
						<div class="media-body">
							<h3 class="media-heading" style="margin-bottom:0">{{ Helper::lastLetterMatch($user->name, 's') ? $user->name ."'" : $user->name . "'s"}} <small>Profile</small></h3>
						    <p>Projects: {{ $user->projects_users()->count() }} <span class="divider">/</span> Discussions: {{ $user->discussions()->count() }} <span class="divider">/</span> Posts: {{ $user->discussion_posts()->count() }}</p>
						</div>
					</div>
				</div>
				<hr style="margin:5px 0"/>
				<div class="row-fluid">
					<div class="span8">
						{{ @file_get_contents( URL::to_route('activities.user.profile', $user->id) ) }}
					</div>
				</div>
			</div>
		</div>
	</div>
</section>