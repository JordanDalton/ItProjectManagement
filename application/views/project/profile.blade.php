<section class="breadcrumb" style="margin-top:-20px">
	<div class="container">
	    <ul style="margin:0">
	    	<li>{{ HTML::link_to_route('home', 'Home') }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('projects', $project->category->name . ' Projects', Str::lower($project->category->name)) }}<span class="divider">/</span></li>
	    	<li class="active">{{ $project->name }}</li>
	    </ul>
	</div>
</section>
<section>
	<div class="container">

		@if( Session::has('permission_error') )
		<div class="alert alert-error">
			<button data-dismiss="alert" class="close" type="button">×</button>
			<strong><i class="icon-flag"></i> Haha, nice try!</strong> Only administrators and project leaders of this project can access {{ HTML::link( Session::get('permission_error_url') ) }}.
		</div>
		@endif

		<div class="row-fluid">
			<div class="span7">

				@if(Auth::check())
					@if($project->isLeader(My_Auth::user()->id))
					<div class="row-fluid">
						<a class="btn" href="{{ URL::to_route('project.update', $project->id) }}"><i class="icon-edit"></i> Edit Project Details</a>
					</div>
					@endif
				@endif

				<div class="row-fluid">
					<h2 style="font-weight:normal;padding-bottom:10px">{{ $project->name }}</h2>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#">Profile</a></li>
						<li>{{ HTML::link_to_route('project.members', 'Members', $project->id) }}</li>
						<li>{{ HTML::link_to_route('project.discussions', 'Discussions', $project->id) }}</li>
						<li>{{ HTML::link_to_route('project.documents', 'Documents', $project->id) }}</li>
					</ul>
				</div>
				<div class="row-fluid">
					<h4>Project <small>Description</small></h4>
					{{ Helper::nl2p(stripslashes($project->description)) }}
				</div>
			</div>
			<div class="span4 offset1">
				<div class="row-fluid">
					{{--@file_get_contents( URL::to_route('activities.project.profile', $project->id) )--}}
				</div>
				<div class="row-fluid">
					<h4><i class="icon-user"></i> People assigned to this project</h4>
					@if($members)
						@foreach($members as $member)
							<a href="{{ URL::to_route('user.profile', $member->user->id) }}"><img class="img-polaroid" src="{{ Helper::avatar($member->user->id) }}" title="{{ $member->user->name }}"/></a>
						@endforeach
					@else
						<p>Nobody is currently assigned.</p>
					@endif
				</div>
				<hr/>
				<div class="row-fluid">
					<h4><i class="icon-bullhorn"></i> Recent Project Discussions <span style="font-size:.7em"><a href="{{ URL::to_route('project.discussions', $project->id)}}">[View All]</a></span></h4>
					<table class="table table-bordered">
						<thead>
							<tr class="btn-success">
								<th>Topics</th>
							</tr>
						</thead>
						<tbody>
							@if($discussions)
								@foreach($discussions as $discussion)
								<tr>
									<td>
										<div><strong>{{ HTML::link_to_route('project.discussion', $discussion->title, $discussion->id) }}</strong></div>
										<div style="color:#777;font-size:.7em">Started By: {{ HTML::link_to_route('user.profile', $discussion->user->name, array($discussion->user->id))}} - Updated {{ Helper::age($discussion->updated_at, true) }}</div style="font-size:.7em">
									</td>
								</tr>
								@endforeach
							@else
								<tr>
									<td><i class="icon-bullhorn"></i> <a href="{{ URL::to_route('project.discussion.create', $project->id)}}">Sound off</a> as the first person to discuss this project.</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
				<div class="row-fluid">
					<h4><i class="icon-file"></i> Reference Documents <span style="font-size:.7em"><a href="{{ URL::to_route('project.documents', $project->id)}}">[View All]</a></span></h4>
					@if($documents)
						<table class="table table-bordered" style="margin-bottom:0">
							<thead>
								<tr class="btn-info">
									<th>Filename</th>
									<th>Uploaded</th>
								</tr>
							</thead>
							<tbody>
								@foreach($documents as $document)
									<tr>
										<td>
											<a href="{{ URL::to_route('document.profile', $document->filename) }}" target="_blank">{{ $document->filename }}</a>
										</td>
										<td>{{ Helper::age($document->created_at, true) }}</td>
									<tr>
								@endforeach
							</tbody>
						</table>
					@else
						<p>No documents have been uploaded.</p>
					@endif
				</div>
				<hr/>
				<div class="row-fluid">
					<h4><i class="icon-file"></i> Recent Activity Feed</h4>
					{{ @file_get_contents( URL::to_route('activities.project.profile', $project->id) ) }}
				</div>
			</div>
		</div>
	</div>
</section>