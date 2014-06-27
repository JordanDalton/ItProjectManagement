<section class="breadcrumb" style="margin-top:-20px">
	<div class="container">
	    <ul style="margin:0">
	    	<li>{{ HTML::link_to_route('home', 'Home') }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('projects', $project->category->name . ' Projects', Str::lower($project->category->name)) }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('project.profile', $project->name, $project->id) }}<span class="divider">/</span></li>
	    	<li class="active">Documents</li>
	    </ul>
	</div>
</section>
<section>
	<div class="container">
		<div class="row-fluid">
			<div class="span3">
				<h2 style="font-weight:normal;letter-spacing:-1px">Project Documents</h2>
			</div>
			<div class="span9">
				<p class="alert" style="margin:10px 0 0 0">The documents here assigned to the <strong>{{ HTML::link_to_route('project.profile', $project->name, $project->id) }}</strong> project.</p>
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
				<p><a class="btn" href="{{ URL::to_route('project.document.create', $project->id) }}"><i class="icon-upload"></i> Upload Document</a></p>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				<hr/>
				@if($documents)
					<table class="table table-bordered">
						<thead>
							<tr class="btn-success">
								<th>Filename</th>
								<th>Uploaded</th>
							</tr>
						</thead>
						<tbody>
							@foreach($documents as $document)
								<tr>
									<td>
										<a href="{{ URL::to_route('document.profile', $document->filename) }}" target="_blank"><i class="icon-file"></i> {{ $document->filename }}</a>
									</td>
									<td>{{ Helper::age($document->created_at, true) }}</td>
								<tr>
							@endforeach
						</tbody>
					</table>
				@else
					<p>No documents have been uploaded yet.</p>
				@endif
			</div>
		</div>
	</div>
</section>