<section class="breadcrumb" style="margin-top:-20px">
	<div class="container">
	    <ul style="margin:0">
	    	<li>{{ HTML::link_to_route('home', 'Home') }}<span class="divider">/</span></li>
	    	<li class="active">My Avatar</li>
	    </ul>
	</div>
</section>
<section>
	<div class="container">
		<div class="row-fluid">
			<div class="span3">
				<h2 style="font-weight:normal;letter-spacing:-1px">My Avatar</h2>
			</div>
		</div>
	</div>
</section>
<hr/>
<section>
	<div class="container">
		<div class="row-fluid">
			<div class="span3">
				<p style="margin-bottom:20px">Select your photo, then click upload.</p>
			{{ $form }}
			</div>
		</div>
	</div>
</section>