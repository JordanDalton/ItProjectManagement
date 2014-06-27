<section class="breadcrumb" style="margin-top:-20px">
	<div class="container">
	    <ul style="margin:0">
	    	<li>{{ HTML::link_to_route('home', 'Home') }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('projects', $project->category->name . ' Projects', Str::lower($project->category->name)) }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('project.profile', $project->name, $project->id) }}<span class="divider">/</span></li>
	    	<li class="active">Members</li>
	    </ul>
	</div>
</section>
<section>
	<div class="container">
		<div class="row-fluid">
			<div class="span3">
				<h2 style="font-weight:normal;letter-spacing:-1px">Project Members</h2>
			</div>
			<div class="span9">
				<p class="alert" style="margin:10px 0 0 0">The members listed here are assigned to the <strong>{{ HTML::link_to_route('project.profile', $project->name, $project->id) }}</strong> project.</p>
			</div>
		</div>
		<div class="row-fluid">
		</div>
	</div>
</section>
<hr/>
<section>
	<div class="container">
	@if(Auth::check())
		@if($isLeader)
		<div class="row-fluid">
			<div class="span2">
					<p><a class="btn" href="{{ URL::to_route('project.members.assign', $project->id) }}"><i class="icon-plus"></i> Assign Member(s)</a></p>
			</div>
		</div>
		@endif
	@endif
		<div class="row-fluid" style="margin-top:20px">
			<div class="span12">
				<div class="row-fluid">
					@if($members)
						<?php $counter = 0;?>
						@foreach($members as $member)
							<div class="span4">
								<div class="media {{ $member->leader ? '' : '' }}">
									<a href="{{ URL::to_route('user.profile', $member->user->id)}}" class="pull-left">
										<img src="{{ Helper::avatar($member->user->id) }} " class="media-object img-polaroid">
									</a>
									<div class="media-body">
										<h4 class="media-heading">{{ $member->user->name }} {{ $member->leader ? Labels::important('Project Leader') : '' }}</h4>
										<p style="color:#777;font-size:.9em;margin-bottom:0">{{ My_Ldap::fetchProfile($member->user->name)->title }} - <a class="btn btn-mini removeMemberFromProject" href="#" user_id="{{ $member->user->id }}" project_id="{{ $member->project_id }}"><i class="icon-remove" style="margin-top:-1px"></i> Remove from Project</a></p>
									</div>
								</div>
								<hr/>
							</div>
							<?php $counter++;?>
							@if($counter % 3 == 0)
								</div>
								<div class="row-fluid">
							@endif
						@endforeach
					@else
						<p>There are current no members assigned to this project.</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</section>