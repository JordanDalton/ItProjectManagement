<section class="breadcrumb" style="margin-top:-20px">
	<div class="container">
	    <ul style="margin:0">
	    	<li>{{ HTML::link_to_route('home', 'Home') }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('projects', $project->category->name . ' Projects', Str::lower($project->category->name)) }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('project.profile', $project->name, $project->id) }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('project.members', 'Members', $project->id) }}<span class="divider">/</span></li>
	    	<li class="active">Assign</li>
	    </ul>
	</div>
</section>
<section>
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<h2 style="font-weight:normal;letter-spacing:-1px">Assign Project Members</h2>
				<p class="alert" style="margin:10px 0 0 0">The members selected will be assigned to the <strong>{{ HTML::link_to_route('project.profile', $project->name, $project->id) }}</strong> project.</p>
			</div>
		</div>
	</div>
</section>
<hr/>
<section>
	<div class="container">
		<div class="row-fluid">
			<div class="span4">
				<form class="form-search">
					<input type="text" class="input-medium search-query" name="name" placeholder="John Smith"/>
					<button id="submitLdapSearch" type="submit" class="btn" project_id="{{ $project->id }}">Search</button>
				</form>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				<table class="table table-bordered">
					<thead class="btn-success">
						<tr>
							<th style="width:43%">Name</th>
							<th style="text-align:center;width:35%">Make Leader</th>
							<th style="width:22%"></th>
						</tr>
					</thead>
					<tbody class="ajaxResults" project_id="{{ $project->id }}">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>