<section class="breadcrumb" style="margin-top:-20px">
	<div class="container">
	    <ul style="margin:0">
	    	<li>{{ HTML::link_to_route('home', 'Home') }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('projects', 'Projects') }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('project.profile', $project->name, $project->id) }}<span class="divider">/</span></li>
	    	<li class="active">Update</li>
	    </ul>
	</div>
</section>
<section>
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<h2 style="font-weight:normal">Update <small>Project</small></h2>
				<p>You are currently updating the project {{ HTML::link_to_route('project.profile', $project->name, $project->id) }}.</p>
			</div>
		</div>
	</div>
</section>
<hr/>
<section>
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				{{ $form }}
			</div>
		</div>
	</div>
</section>