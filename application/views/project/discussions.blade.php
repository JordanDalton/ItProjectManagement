<section class="breadcrumb" style="margin-top:-20px">
	<div class="container">
	    <ul style="margin:0">
	    	<li>{{ HTML::link_to_route('home', 'Home') }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('projects', $project->category->name . ' Projects', Str::lower($project->category->name)) }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('project.profile', $project->name, $project->id) }}<span class="divider">/</span></li>
	    	<li class="active">Discussions</li>
	    </ul>
	</div>
</section>
<section>
	<div class="container">
		<div class="row-fluid">
			<div class="span3">
				<h2 style="font-weight:normal;letter-spacing:-1px">Project Discussions</h2>
			</div>
			<div class="span9">
				<p class="alert" style="margin:10px 0 0 0">The discussions here relate to the <strong>{{ HTML::link_to_route('project.profile', $project->name, $project->id) }}</strong> project.</p>
			</div>
		</div>
		<div class="row-fluid">
		</div>
	</div>
</section>
<hr/>
<section>
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<p><a class="btn btn-large" href="{{ URL::to_route('project.discussion.create', $project->id)}}"><i class="icon-plus"></i> Create Topic</a></p>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<table class="table table-striped">
					<thead>
						<tr class="btn-success">
							<th style="width:80%">Topic Name</th>
							<th style="text-align:center;width:10%">Last Updated</th>
							<th style="text-align:center;width:10%">Posts</th>
						</tr>
					</thead>
					<tbody>
						@if($discussions->results)
							@foreach($discussions->results as $discussion)
							<tr>
								<td>
									<strong>{{ HTML::link_to_route('project.discussion', $discussion->title, $discussion->id) }}</strong>
									<p style="color:#777;font-size:.7em">Started By: {{ HTML::link_to_route('user.profile', $discussion->user->name, $discussion->user->id) }}</p>
								</td>
								<td style="text-align:center">
									{{ Helper::age($discussion->updated_at, true) }}
								</td>
								<td style="text-align:center">{{ $discussion->post_count }}</td>
							</tr>
							@endforeach
						@else
							<tr>
								<td colspan="3">There are curerntly no discussions to list.</td>
							</tr>
						@endif
					</tbody>
				</table>
				{{ $discussions->appends($appends)->links() }}
			</div>
		</div>
	</div>
</section>