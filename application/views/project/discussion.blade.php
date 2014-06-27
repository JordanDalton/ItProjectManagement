<section class="breadcrumb" style="margin-top:-20px">
	<div class="container">
	    <ul style="margin:0">
	    	<li>{{ HTML::link_to_route('home', 'Home') }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('projects', $discussion->project->category->name . ' Projects', Str::lower($discussion->project->category->name)) }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('project.profile', $discussion->project->name, $discussion->project->id) }}<span class="divider">/</span></li>
	    	<li>{{ HTML::link_to_route('project.discussions', 'Discussions', $discussion->project->id) }}<span class="divider">/</span></li>
	    	<li class="active">{{ $discussion->title }}</li>
	    </ul>
	</div>
</section>
<section>
	<div class="container">
		<h2 style="font-weight:normal">{{ $discussion->title }}</h2>
	</div>
</section>
<section>
	<div class="container">
		<hr/>
		<div class="row-fluid">
			<div class="span6">
				<button class="btn btn-large scrollToBottom"><i class="icon-comment"></i> Post Reply</button>
			</div>
			<div class="span6 noPaginationMargins" style="text-align:right">
				{{ $posts->links() }}
			</div>
		</div>
		<hr/>
		<div class="row-fluid">
			<div class="span12">
				<table class="table table-striped">
					<thead>
						<tr class="btn-success">
							<th style="text-align:center;width:100px;">User</th>
							<th>Post</th>
						</tr>
					</thead>
					<tbody>
						@foreach($posts->results as $post)
						<tr>
							<td style="text-align:center">
								<img class="img-polaroid" src="{{ Helper::avatar($post->user->id) }}"/>
								<p style="font-size:.8em;margin-bottom:0">{{ HTML::link_to_route('user.profile', $post->user->name, $post->user->id) }}</p>
							</td>
							<td>
								<a name="{{ $post->id }}"></a> 
								<p style="background:#eee;color:#666;font-size:.7em">Posted {{ $post->created_at }}</p>
								<div post_id="{{ $post->id }}">{{ $post->post }}</div>
								<hr/>
								<div style="text-align:right">
									<button class="btn btn-small quoteReply" post_id="{{ $post->id }}" post_name="{{ $post->user->name }}"><i class="icon-th-large"></i> Quote</button>
								</div>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		<div class="row-fluid">
			{{ $posts->links() }}
		</div>
		<div class="row-fluid">
			{{ $form }}
		</div>
	</div>
</section>