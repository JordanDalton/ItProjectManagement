<section class="breadcrumb" style="margin-top:-20px">
	<div class="container">
	    <ul style="margin:0">
	    	<li>{{ HTML::link_to_route('home', 'Home') }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('user.profile', $user->name, $user->id) }}<span class="divider">/</span></li>
	    	<li class="active">{{ ucfirst($project_category) }} Projects</li>
	    </ul>
	</div>
</section>
<section>
	<div class="container">
		<div class="row-fluid">
			<div class="span6">
				<h2>{{ Helper::apostrophes($user->name) }} <span style="color:#999;font-weight:normal">{{ ucfirst($project_category) }} Projects</span></h2>
			</div>
			<div class="span6">
			</div>
		</div>
	</div>
</section>
<hr/>
<section>
	<div class="container">
		<div class="row-fluid">
			<div class="span6">
				<ul class="nav nav-pills">
					@foreach($available_categories as $available_category)
						<li class="{{ URI::segment(4) === Str::lower($available_category->name) ? 'active' : ''; }}">
							{{ HTML::link_to_route('user.projects', ucfirst($available_category->name), array($user->id, Str::lower($available_category->name)) )}}
						</li>
					@endforeach
				</ul>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<table class="table table-striped">
					<thead>
						<tr class="btn-success">
							<th style="width:70%">Project Name</th>
							<th style="text-align:center;width:10%">Discussions</th>
							<th style="text-align:center;width:10%">Members</th>
							<th style="text-align:center;width:10%">Documents</th>
						</tr>
					</thead>
					<tbody>
						@if($projects->results)
							@foreach($projects->results as $project)
							<tr>
								<td>
									<strong>{{ HTML::link_to_route('project.profile', $project->name, $project->id) }}</strong>
								</td>
								<td style="text-align:center">{{ HTML::link_to_route('project.discussions', $project->discussion_count, $project->id) }}</td>
								<td style="text-align:center">{{ HTML::link_to_route('project.members', $project->member_count, $project->id) }}</td>
								<td style="text-align:center">{{ HTML::link_to_route('project.documents', $project->document_count, $project->id) }}</td>
							</tr>
							@endforeach
						@else
							<tr>
								<td colspan="4">There are currently no projects to list.</td>
							</tr>
						@endif
					</tbody>
				</table>
				{{ $projects->appends($appends)->links() }}
			</div>
		</div>

	</div>
</section>