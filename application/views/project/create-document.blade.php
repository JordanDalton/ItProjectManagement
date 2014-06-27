<section class="breadcrumb" style="margin-top:-20px">
	<div class="container">
	    <ul style="margin:0">
	    	<li>{{ HTML::link_to_route('home', 'Home') }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('projects', $project->category->name . ' Projects', Str::lower($project->category->name)) }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('project.profile', $project->name, $project->id) }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('project.discussions', 'Discussions', $project->id) }}<span class="divider">/</span></li>
	    	<li class="active">Create Discussion Topic</li>
	    </ul>
	</div>
</section>
<section>
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<h2 style="font-weight:normal;letter-spacing:-1px">Upload Document</h2>
				<p>This will be assigned to the <strong>{{ HTML::link_to_route('project.profile', $project->name, $project->id) }}</strong> project.</p>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				{{ $form }}
			</div>
		</div>
	</div>
</section>